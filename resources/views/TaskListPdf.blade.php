<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        ul, #myUL {
            list-style-type: none;
        }

        .active {
            display: block;
        }
        .header-pdf {
            padding: 0px 50px;

        }
        #myUL {
            padding: 20px 50px;
        }
    </style>
</head>
<body>
<div class="header-pdf">
    <h1> List Title</h1>
    <p style="border-bottom: 1px solid;">List Description</p>
</div>

<ul id="myUL">
    <li>
        <span class="caret">Beverages</span>
        <ul class="nested">
            <li>Water</li>
            <li>Coffee</li>
            <li><span class="caret">Tea</span>
                <ul class="nested">
                    <li>Black Tea</li>
                    <li>White Tea</li>
                    <li><span class="caret">Green Tea</span>
                        <ul class="nested">
                            <li>Sencha</li>
                            <li>Gyokuro</li>
                            <li>Matcha</li>
                            <li>Pi Lo Chun</li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
</ul>

<script>

</script>

</body>
</html>
