<?php

namespace Tests\Unit;

use App\Console\Commands\CreateSurveyMigrationsFromDocument;
use App\Survey;
use App\SurveyResponse;
use App\User;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Cookie\CookieValuePrefix;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Guards\TokenGuard;
use Laravel\Passport\Http\Middleware\CreateFreshApiToken;
use Laravel\Passport\Passport;
use Laravel\Passport\PassportUserProvider;
use Laravel\Passport\TokenRepository;
use League\OAuth2\Server\ResourceServer;
use Mockery;
use Tests\CreatesApplication;

// These compatibility checks do not require a populated application database.
class Laravel11CompatibilityTest extends TestCase
{
    use CreatesApplication;

    public function test_browser_api_cookie_authentication_and_csrf_validation(): void
    {
        $user = new User();
        $user->id = 123;
        $session = $this->app['session']->driver();
        $session->start();
        $session->regenerateToken();

        $webRequest = Request::create('/', 'GET');
        $webRequest->setLaravelSession($session);
        $webRequest->setUserResolver(fn () => $user);
        $response = $this->app->make(CreateFreshApiToken::class)->handle(
            $webRequest,
            fn () => new Response('Authenticated page')
        );
        $cookie = collect($response->headers->getCookies())->first(
            fn ($cookie) => $cookie->getName() === Passport::cookie()
        );
        $this->assertNotNull($cookie);

        $encrypter = $this->app['encrypter'];
        $encryptedCookie = $encrypter->encrypt(
            CookieValuePrefix::create(Passport::cookie(), $encrypter->getKey()).$cookie->getValue(),
            Passport::$unserializesCookies
        );
        $provider = Mockery::mock(UserProvider::class);
        $provider->shouldReceive('retrieveById')->once()->with(123)->andReturn($user);
        $makeGuard = function ($csrf) use ($provider, $encrypter, $encryptedCookie) {
            $request = Request::create('/api/user', 'GET', [], [Passport::cookie() => $encryptedCookie]);
            // Axios sends the encrypted XSRF-TOKEN cookie in this header.
            $request->headers->set('X-XSRF-TOKEN', $encrypter->encrypt(
                CookieValuePrefix::create('XSRF-TOKEN', $encrypter->getKey()).$csrf,
                TokenGuard::serialized()
            ));

            return new TokenGuard(
                Mockery::mock(ResourceServer::class),
                new PassportUserProvider($provider, 'users'),
                Mockery::mock(TokenRepository::class),
                Mockery::mock(ClientRepository::class),
                $encrypter,
                $request
            );
        };

        $this->assertSame($user, $makeGuard($session->token())->user());
        $this->assertTrue($user->token()->transient());
        $this->assertNull($makeGuard('incorrect-csrf')->user());
        $this->assertSame('passport', config('auth.guards.api.driver'));
        $this->assertTrue(Passport::$passwordGrantEnabled);
    }

    public function test_survey_migration_command_uses_native_schema_inspection(): void
    {
        $command = $this->app->make(Kernel::class)->all()['survey:migration'];
        $this->assertInstanceOf(CreateSurveyMigrationsFromDocument::class, $command);
        $this->assertSame($command::class, $this->app->make('command.survey.migration')::class);

        $template = $command->getDefaultText();
        $this->assertStringNotContainsString('getDoctrineSchemaManager', $template);
        $this->assertStringContainsString("Schema::getIndexes('DummyTable')", $template);
        $this->assertStringContainsString("'respondent_morph_index'", $template);
    }

    public function test_dynamic_survey_models_and_validation_boot(): void
    {
        $survey = new Survey(['name' => 'Application', 'response_table' => 'rsp_application_1']);
        $survey->id = 1;
        $respondent = new User();
        $respondent->id = 123;
        $response = $survey->getNewResponse($respondent);

        $this->assertInstanceOf(SurveyResponse::class, $response);
        $this->assertSame('rsp_application_1', $response->getTable());
        $this->assertSame(123, $response->respondent_id);
        $this->assertSame(1, $response->survey_id);
        $response->finalized_at = '2026-01-01 12:00:00';
        $this->assertInstanceOf(\Carbon\CarbonInterface::class, $response->finalized_at);
        $this->assertTrue(validator(['age' => 18], ['age' => 'int_min:18'])->passes());
        $this->assertTrue(validator(['age' => 17], ['age' => 'int_min:18'])->fails());
        $this->assertTrue($this->app['events']->hasListeners('eloquent.saving: '.SurveyResponse::class));
    }

    public function test_legacy_factory_still_makes_models(): void
    {
        $group = factory(\App\WorkingGroup::class)->make();
        $this->assertInstanceOf(\App\WorkingGroup::class, $group);
        $this->assertFalse($group->exists);
        $this->assertNotEmpty($group->name);
    }
}
