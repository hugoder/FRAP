<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="style.css"/>
        <title>Drag and drop véhicules</title>
    </head>
    <body>
    
        <div class="vehicules">
        <h1>Véhicules</h1>
            <div class="origin">
                <img src="VSAV.png" id="draggable-1" class="draggable" draggable="true" ondragstart="onDragStart(event);">
                <img src="FPT.png" id="draggable-2" class="draggable" draggable="true" ondragstart="onDragStart(event);">
                <img src="EPAS.png" id="draggable-3" class="draggable" draggable="true" ondragstart="onDragStart(event);">
                <img src="VSR.png" id="draggable-4" class="draggable" draggable="true" ondragstart="onDragStart(event);">
                <img src="VSAV.png" id="draggable-5" class="draggable" draggable="true" ondragstart="onDragStart(event);">
                <img src="FPT.png" id="draggable-6" class="draggable" draggable="true" ondragstart="onDragStart(event);">
                <img src="EPAS.png" id="draggable-7" class="draggable" draggable="true" ondragstart="onDragStart(event);">
                <img src="VSR.png" id="draggable-8" class="draggable" draggable="true" ondragstart="onDragStart(event);">
                <img src="VSAV.png" id="draggable-9" class="draggable" draggable="true" ondragstart="onDragStart(event);">
                <img src="FPT.png" id="draggable-10" class="draggable" draggable="true" ondragstart="onDragStart(event);">
                <img src="EPAS.png" id="draggable-11" class="draggable" draggable="true" ondragstart="onDragStart(event);">
            </div>
        </div>

        <div class="carte">
            <div class="dropzone" ondragover="onDragOver(event);" ondrop="onDrop(event);">
                    Carte
            </div>
        </div>

        <script src="script.js"></script>

    </body>
</html>