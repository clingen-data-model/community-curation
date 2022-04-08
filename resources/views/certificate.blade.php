<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{$curationActivity}}</title>
    <style>
        :root {
            --blue: rgb(0,74,173);
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
        }
        .actionability {
            --highlight: #f7941d;
            --highlight-lighter: #f8a94a;
        }
        .baseline {
            --highlight: #e74c3c;
            --highlight-lighter: #eb7063;
        }
        .dosage {
            --highlight: #ee3882;
            --highlight-lighter: #f1609b;
        }
        .gene {
            --highlight: rgb(20,168,158);
            --highlight-lighter: #42b8b0;
        }
        .somatic {
            --highlight: #980f84;
            --highlight-lighter: #ae3f9c;
        }
        .variant {
            --highlight: #8cc63f;
            --highlight-lighter: #a3d166;
        }
        h1 {
            color: var(--blue);
            text-transform: uppercase;
            font-weight: bold;
            font-size: 
        }
        .boundary {
            position: relative;
            box-sizing: border-box;
            width: 11in;
            background: repeating-linear-gradient(-45deg, var(--highlight), var(--highlight) 20px, var(--highlight-lighter) 20px, var(--highlight-lighter) 40px);
            padding: .375in;
        }
        .container {
            position: relative;
            box-sizing: border-box;
            width: 10.25in;
            height: 7.75in;
            padding: .125in;
            background-color: #f6faf7;
            text-align:center;
        }
        .logo-image {
            margin: auto;
            display: block;
        }
        .flex {
            display: flex;
            margin: auto;
        }
        .name {
            width: 90%;
            
            margin: auto;
            margin-bottom: .25in;
            border-bottom: solid 4px;
            border-color: var(--highlight);
            padding-bottom: .05in;
            
            color: var(--blue);
            font-size: 60px;
            font-weight: bold;
        }
        .sig-container {
            width: 38%;
        }
        .seal {
            margin-left: .5in;
            margin-right: .5in;
        }
        .serif {
            font-family: serif;
            font-weight: normal;
            font-size: 32px;
        }
        .sig {
            color: var(--blue);
            margin-bottom: .125in;
            border-bottom: 5px solid;
            padding-bottom: .05in;
            font-size: 24px;
        }
        .sig-label {
            text-transform: uppercase;
            font-size: 16px;
            font-weight: bold;
            color:  var(--highlight);
        }
        .email-container {
            position: absolute;
            bottom: .25in;
            width: 100%;
        }
        .email {
            font-weight: bold;
            font-size: 18px;
            color: var(--blue);
        }
        .type-logo {
            position: absolute; 
            top: .125in; 
            right: .125in;
            width: 150px;
        }
    </style>
</head>
<body class="{{$type}}">
    <div class="boundary">
        <div class="container center">
            <img src="/images/certificates/{{$type}}-logo.png" alt="" class="type-logo">
            <img src="/images/certificates/clingen-logo.png" alt="ClinGen Logo" class="logo-image">
            
            <h1>Certificate of completion</h1>
            <div class="serif" style="margin-bottom: .25in">This certifies that</div>
            
            <div class="name">{{$name}}</div>

            <div class="serif">has successfully completed <br> ClinGen {{$curationActivity}} Curation Training</div>

            <div class="flex" style="width: 80%; margin-top: .375in;">
                <div class="sig-container">
                    <div class="sig">C3 WG</div>
                    <div class="sig-label">ClinGen Community<br>Curation Working <br>Group</div>
                </div>
                <img src="/images/certificates/seal.png" alt="seal" class="seal">
                <div class="sig-container">
                    <div class="sig">{{$date->format('m/d/Y')}}</div>
                    <div class="sig-label">Training Date</div>
                </div>
            </div>
            <div class="email-container">
                <div class="email">volunteer@clinicalgenome.org</div>
            </div>
        </div>
    </div>
</body>
</html>