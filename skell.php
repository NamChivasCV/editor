<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <title>Pe0nj</title>
    <style type="text/css" media="screen">
        * {
            padding: 0;
            margin: 0;
        }

        .home {
            border: 1px solid #656565;
            width: calc(100vw - (100vw * .1));
            height: calc(100vh - (100vh * .1));
            margin: calc(100vh * .1 / 2) auto;
            display: grid;
            grid-template-columns: 50% 50%;
        }

        #editor {
            width: 100%;
            height: 100%;
        }

        #results {
            padding: 8px;
            background-color: #272822;
            position: relative;
            overflow: hidden;
            overflow: auto;
            border: 1px solid #656565;
            color: #888888;
        }

        #btn-run {
            border-radius: 5px;
            border: 1px solid;
            margin: 8px;
            outline: 0;
            position: absolute;
            top: calc(100vh * .1 / 2);
            right: 50%;
            padding: 4px 8px;
            background-color: #767676;
            color: #fff;
            font-size: 16px;
        }
    </style>
</head>

<body style="background: #14171a !important;">
    <div class="home">
        <div id="editor">&lt;?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define("HOME", $_SERVER['DOCUMENT_ROOT']);

try{




}catch(Exception $e){
    echo "Error: " . $e;
}



function withText($content){
    return "&lt;textarea style='width: 100%; height: 100%' >" . $content . "&lt;/textarea>";
}




</div>

        <aside id="results">

        </aside>
    </div>

    <button id="btn-run">Run</button>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.14/ace.js" type="text/javascript" charset="utf-8"></script>
    <script>
        var editor = ace.edit("editor");
        editor.setTheme("ace/theme/monokai");
        editor.session.setMode("ace/mode/php");




        $('#btn-run').click(() => {
            runCode();
        })



        //
        $('#editor').keydown(function(evt) {
            evt.which == 120 && runCode();
        })





        $('#btn-run').css('background-color', "#2ecc71")
        $('#btn-run').css('transition', "0.3s")

        function runCode() {
            $('#btn-run').css('background-color', "#95a5a6")
            $('#btn-run').html('Running...')
            setTimeout(() => {
                let mAbc = editor.getValue().replace('\<\?php', '');
                $.post('index.php', {
                    abc: btoa(mAbc),
                }, (data) => {

                    $('#results').html('');
                    $('#results').html(data);
                    $('#btn-run').html('Run')
                    $('#btn-run').css('background-color', "#2ecc71")
                })
            }, 1000);
        }
    </script>





</body>

</html>