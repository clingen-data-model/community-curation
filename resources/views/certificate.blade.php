<?php
    $blue = 'rgb(0,74,173)';
    $highlights = [
        'actionability' => [
            '#f7941d',
            '#f8a94a'
        ],
        'baseline' => [
            '#e74c3c',
            '#eb7063'
        ],
        'dosage' => [
            '#ee3882',
            '#f1609b'
        ],
        'gene' => [
            'rgb(20,168,158)',
            '#42b8b0'
        ],
        'somatic' => [
            '#980f84',
            '#ae3f9c'
        ],
        'variant' => [
            '#8cc63f',
            '#a3d166'
        ],
    ];

    [$highlight, $hlLighter] = $highlights[$type];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{$curationActivity}}</title>
    <style>
        body {
            font-family: montserrat, times, sans-serif;
            margin: 0;
            font-kerning: none;
        }
        div {
            {{-- border: solid 1px #f0f; --}}
        }
        h1 {
            text-transform: uppercase;
            font-weight: bold;
            color: {{$blue}};
            margin: .125in;
            font-size: 30pt;
        }

        .serif {
            font-family: lora;
            font-style: italic;
            font-weight: normal;
            font-size: 30px;
        }

        .boundary {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background: repeating-linear-gradient(-45deg, {{$highlight}}, {{$highlight}} 20px, {{$hlLighter}} 20px, {{$hlLighter}} 40px);
        }
        .container {
            margin: .375in;
            position: relative;
            box-sizing: border-box;
            background-color: #f6faf7;
            text-align:center;
            height: 8.25in;
        }
        .logo-image {
            margin: auto;
            margin-top: 10pt;
            display: block;
        }

        .recipient {
            width: 90%;
            
            margin: auto;
            margin-bottom: .5in;
            border-bottom: solid 4px #f0f;
            border-color: {{$highlight}};
            
            color: {{$blue}};
            font-size: 60px;
            font-weight: bold;
            border-color: {{$highlight}};
        }

        .sig-and-date {
            width: 7.75in; 
            margin: auto;
            margin-top: .375in; 
            text: center;
            overflow: hidden;
            {{-- border: solid 2px purple; --}}
        },
        .sig-block {
            width: 100px;
            float: left;
        }
        .sig-and-date div.seal{
            width: 1.5in;
        }
        .sig {
            font-family: opensans;
            font-weight: bold;
            display: block;
            color: {{$blue}};

            margin-bottom: 5pt;
            border-bottom: 5px solid {{$blue}};
            padding-bottom: .05in;
            
            font-size: 24px;
        }
        .sig-label {
            padding-top: 10pt;
            text-transform: uppercase;
            display: block;
            font-size: 12pt;
            font-weight: bold;
            color:  {{$highlight}};
        }

        .type-logo {
            position:absolute;
            background: #f0f; 
            top: .75in; 
            right: .75in;
            width: 1.5in; 
        }
        .email-container {
            position: absolute;
            bottom: .75in;
            left: 0;
            width: 100%;
        }
        .email {
            text-align: center;
            width: 6in;
            margin: auto;
            font-weight: bold;
            font-size: 18px;
            color: {{$blue}};
        }
    </style>
</head>
<body class="{{$type}}">
    <div class="boundary">
        <div class="container center">
            <img src="/images/certificates/logo-clinical-genome-logo-vector.svg" alt="ClinGen Logo" class="logo-image">
            <br>
            <h1 style="margin-bottom: 10pt">Certificate of completion</h1>
            <div class="serif" style="margin: 20pt">This certifies that</div>
            <div class="recipient">{{$name}}</div>

            <br>
            <div class="serif">has successfully completed <br> ClinGen {{$curationActivity}} Curation Training</div>

            <div class="sig-and-date">
                {{-- Not sure why, but the first element always has a right margin on it --}}
                <div class="sig-block" style="width: 0px;"></div>
                
                <div class="sig-block" style="width: 2.5in">
                    <div class="sig" style="font-style: italic; font-family: lora">C3 WG</div>
                    <div class="sig-label">ClinGen Community<br>Curation Working <br>Group</div>
                </div>
                <div class="sig-block" style="width: 1.5in">
                    <img src="/images/certificates/seal.jpg" alt="seal">
                </div>
                <div class="sig-block" style="width: 2.5in">
                    <div class="sig">{{$date->format('F d, Y')}}</div>
                    <div class="sig-label">Training Date</div>
                </div>
            </div>
        </div>
    </div>
    <div class="email-container">
        <div class="email">volunteer@clinicalgenome.org</div>
    </div>
    <div class="type-logo">
        <img src="/images/certificates/{{$type}}-logo.jpg" alt="curation activity logo" class="type-logo">
    </div>
</body>
</html>