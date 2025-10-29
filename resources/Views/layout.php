<html lan="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@var('title')</title>
        <link rel="stylesheet" href="/css/styles.css">
        <script src="/js/scripts.js" defer></script>
    </head>
    <body>

        <nav>
            <ul>
                <li>Nav 1</li>
                <li>Nav 2</li>
                <li>Nav 3</li>
            </ul>
        </nav>

        @holder('body')
    </body>
</html>