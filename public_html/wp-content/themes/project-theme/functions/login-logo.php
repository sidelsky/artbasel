<?php
function my_login_logo() { ?>
    <style type="text/css">
         body {
            background: #f1f1f1;
            min-width: 0;
            color: #444;
            font-size: 13px;
            line-height: 1.4;
         }

         body.login-action-login,
         body.login-action-lostpassword {
            background-color: #202020;
            min-width: 0;
            color: #000;
            font-size: 13px;
            line-height: 1.4;
            display: flex;
            justify-content: center;
            align-items: center;
         }

         body.login-action-login label {
            color: #fff;
         }

         body #login {
            padding: 0px;
         }

         body #login h1 a, .login h1 a {
            /* background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/site-login-logo.png); */
            $color: fff;
            background-image: url('data:image/svg+xml,%3Csvg%20width%3D%22183%22%20height%3D%2214%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M2.281%205.691H8.27V.525h2.197v13.151H8.27V7.654H2.281v6.022H0V.526h2.281v5.165zm171.582.056h5.988V.58h2.198v13.15h-2.198V7.708h-5.988v6.021h-2.282V.58h2.282v5.167zM23.385%209.387l-2.08-6.19-1.946%206.19h4.026zm-7.833%204.344l4.411-13.2h2.734l4.428%2013.2h-2.23l-.84-2.466h-5.384l-.787%202.466h-2.332zM34.76%208.548c0%20.95.29%201.759.872%202.423.58.667%201.296.999%202.147.999.96%200%201.705-.391%202.23-1.174.425-.649.638-1.398.638-2.248V.497h2.197v8.437c0%201.398-.459%202.578-1.375%203.54-.962%201.017-2.192%201.525-3.69%201.525-1.51%200-2.76-.475-3.75-1.425-.989-.95-1.484-2.165-1.484-3.64V.497h2.214v8.051h.001zm18.93-6.295c-.727%200-1.286.17-1.677.512-.392.341-.587.785-.587%201.333%200%20.828.591%201.37%201.778%201.627l2.885.62c.793.169%201.443.537%201.946%201.108.525.604.787%201.32.787%202.147%200%201.264-.441%202.304-1.324%203.12-.884.816-2.047%201.224-3.49%201.224-1.14%200-2.07-.174-2.792-.52-.722-.346-1.53-.995-2.424-1.946l1.694-1.425c1.14%201.23%202.315%201.845%203.522%201.845%201.778%200%202.668-.593%202.668-1.778%200-.917-.543-1.493-1.628-1.727l-2.683-.57c-.951-.214-1.711-.666-2.281-1.36-.56-.66-.84-1.42-.84-2.28%200-1.186.426-2.132%201.275-2.835C51.37.643%2052.516.29%2053.958.29a8.346%208.346%200%20011.846.285c1.33.357%202.258.99%202.784%201.895l-1.795%201.325c-.604-.95-1.639-1.465-3.103-1.543v.001zm13.628%203.69h5.635v1.963h-5.519v3.74h6.375v2.03h-8.706V.476h8.32v1.962h-6.105v3.505zm17.584.604c1.51%200%202.264-.654%202.264-1.962%200-.65-.205-1.152-.612-1.51-.408-.357-.942-.536-1.602-.536h-2.667v4.008h2.617zm2.432%207.13l-2.684-5.15h-2.365v5.15h-2.198V.575h4.73c1.455%200%202.578.419%203.372%201.258.693.75%201.04%201.722%201.04%202.919a5.617%205.617%200%2001-.285%201.526c-.38%201.063-1.051%201.716-2.012%201.962l2.733%205.435h-2.331v.002zm28.916.054L113.044.497h2.349l2.247%208.705%201.996-8.705h2.6l2.13%208.705%202.097-8.705h2.299l-3.037%2013.234h-2.5l-2.28-9.746-2.18%209.746h-2.517.002zm17.953%200h2.298V.513h-2.298v13.218zm13.869-7.184c1.51%200%202.265-.654%202.265-1.962%200-.65-.205-1.152-.612-1.51-.408-.357-.942-.536-1.601-.536h-2.667v4.008h2.615zm2.433%207.13l-2.684-5.15h-2.364v5.15h-2.199V.575h4.731c1.454%200%202.577.419%203.372%201.258.693.75%201.04%201.722%201.04%202.919a5.618%205.618%200%2001-.285%201.526c-.381%201.063-1.052%201.716-2.014%201.962l2.734%205.435h-2.331v.002zm15.947-11.072h-3.607v11.071h-2.198V2.605h-3.623V.593h9.428v2.012zm-64.76.22c-.658%200-.987.378-.987%201.132%200%20.417.151.809.45%201.176.29-.077.581-.252.87-.522.33-.3.495-.59.495-.871%200-.61-.276-.915-.828-.915zm-2.264%206.023c0%20.494.203.956.61%201.386.406.431.856.646%201.35.646.174-.01.338-.034.493-.072.368-.097.658-.267.87-.508l-2.22-3.12c-.687.32-1.054.876-1.103%201.668zm5.166-1.349h1.83c-.02.978-.427%202.002-1.22%203.077.166.183.411.285.74.304-.009%200%20.223-.03.697-.087l.203%201.669c-.28.106-.638.164-1.074.174-.444%200-.798-.057-1.06-.174-.212-.096-.487-.3-.826-.61-.6.365-1.05.592-1.35.68-.232.07-.6.104-1.103.104-1.006%200-1.88-.375-2.62-1.125-.74-.752-1.11-1.597-1.11-2.536a4.63%204.63%200%2001.233-1.207c.319-.89.88-1.526%201.683-1.903-.446-.436-.683-1.104-.712-2.006%200-.659.247-1.263.74-1.816.533-.581%201.152-.872%201.859-.872.793%200%201.444.259%201.952.777s.76%201.144.76%201.88c-.027.367-.11.71-.245%201.03-.329.754-.9%201.32-1.712%201.698l1.813%202.452c.088-.116.165-.247.233-.391.155-.34.25-.712.289-1.118z%22%20fill%3D%22%23FFF%22%20fill-rule%3D%22nonzero%22%2F%3E%3C%2Fsvg%3E');
            height: 20px;
            width: 320px;
            margin-bottom: 30px;
            background-size: contain;
            background-repeat: no-repeat;
        }

        body.login #login_error, 
        body.login .message, 
        body.login .success {
            border-left: 4px solid red;
            padding: 12px;
            margin-left: 0;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
         }

         .login #loginform {
            margin-top: 20px;
            margin-left: 0;
            padding: 26px 20px 46px;
            font-weight: 400;
            overflow: hidden;
            background: transparent;
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,.04);
         }

         #loginform input[type=checkbox]:focus, 
         #loginform input[type=color]:focus, 
         #loginform input[type=date]:focus, 
         #loginform input[type=datetime-local]:focus, 
         #loginform input[type=datetime]:focus, 
         #loginform input[type=email]:focus, 
         #loginform input[type=month]:focus, 
         #loginform input[type=number]:focus, 
         #loginform input[type=password]:focus, 
         #loginform input[type=radio]:focus, 
         #loginform input[type=search]:focus, 
         #loginform input[type=tel]:focus, 
         #loginform input[type=text]:focus, 
         #loginform input[type=time]:focus, 
         #loginform input[type=url]:focus, 
         #loginform input[type=week]:focus, 
         #loginform select:focus, 
         #loginform textarea:focus {
            border-color: #ccc;
            box-shadow: 0 0 0 1px #000;
            outline: 2px solid transparent;
         }

         #loginform #wp-submit {
            background: #000;
            border-color: #ccc;
            color: #fff;
            text-decoration: none;
            text-shadow: none;
         }

         body #nav {
            display: none;
            visability: hidden;
         }

         body p#backtoblog {
            padding: 0 !important;
         }

         body.login #backtoblog a, 
         body.login #nav a {
            text-decoration: none;
            color: #fff;
         }

         .wp-core-ui .button, 
         .wp-core-ui 
         .button-secondary {
            color: #000 !important;
            border: 0 !important;
            background: #fff !important;
            vertical-align: top;
         }

         .login .button.wp-hide-pw {
            padding: 0px !important;
            border: 0 !important;
            height: 10px;
            border: 1px solid transparent !important;
            transform: scale(.9); 
         }


         .button-primary:focus {
            box-shadow: 0 0 0 1px #fff, 0 0 0 3px #ccc !important;
         }


    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );