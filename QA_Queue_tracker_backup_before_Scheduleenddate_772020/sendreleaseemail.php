<?
####Autoemail to the group once the database is released 
$htmlContent = "
    <html>
    <head>
        <title>Welcome to CodexWorld</title>
    </head>
    <body>
        <h1>Thanks you for joining with us!</h1>
        <table cellspacing=0 style=border: 2px dashed #FB4314; width: 300px; height: 200px;>
            <tr>
                <th>Name:</th><td>CodexWorld</td>
            </tr>
            <tr style=background-color: #e0e0e0;>
                <th>Email:</th><td>contact@codexworld.com</td>
            </tr>
            <tr>
                <th>Website:</th><td><a href=http://www.codexworld.com>www.codexworld.com</a></td>
            </tr>
        </table>
    </body>
    </html>";


?>
<a href="mailto:parmindervirdi@gmail.com?subject=Feedback for webdevelopersnotes.com&body="<?echo $htmlContent;?>>Send me an email</a>
