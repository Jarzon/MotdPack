<h1 class="rules">Add a song</h1>
<a href="/music/">< Songs</a>

<p>
    The server is limited to upload only: <?=ini_get('max_file_uploads')?> files and <?=$maxUpload?>.<br>
</p>

<form enctype="multipart/form-data" method="post">
    <label for="upload">Upload:</label>
    <input id="upload" name="upload[]" type="file" accept="audio/*" multiple><br>

    <div style="text-align: center;"><input name="submit" type="submit" value="Upload"></div>
</form>

<?php /*<script>
        window.URL = window.URL || window.webkitURL;

        var fileSelect = document.getElementById("fileSelect"),
            fileElem = document.getElementById("fileElem"),
            fileList = document.getElementById("fileList");

        fileSelect.addEventListener("click", function (e) {
            if (fileElem) {
                fileElem.click();
            }
            e.preventDefault(); // prevent navigation to "#"
        }, false);

        function handleFiles(files) {
            if (!files.length) {
                fileList.innerHTML = "<p>No files selected!</p>";
            } else {
                fileList.innerHTML = "";
                var list = document.createElement("ul");
                fileList.appendChild(list);
                for (var i = 0; i < files.length; i++) {
                    var li = document.createElement("li");
                    list.appendChild(li);

                    var img = document.createElement("img");
                    img.src = window.URL.createObjectURL(files[i]);
                    img.height = 60;
                    img.onload = function() {
                        window.URL.revokeObjectURL(this.src);
                    }
                    li.appendChild(img);
                    var info = document.createElement("span");
                    info.innerHTML = files[i].name + ": " + files[i].size + " bytes";
                    li.appendChild(info);
                }
            }
        }

        document.querySelector('input[type="file"]').onchange = function(e) {
            var reader = new FileReader();

            reader.onload = function(e) {
                var dv = new jDataView(this.result);

                // "TAG" starts at byte -128 from EOF.
                // See http://en.wikipedia.org/wiki/ID3
                if (dv.getString(3, dv.byteLength - 128) == 'TAG') {
                    var title = dv.getString(30, dv.tell());
                    var artist = dv.getString(30, dv.tell());
                    var album = dv.getString(30, dv.tell());
                    var year = dv.getString(4, dv.tell());
                } else {
                    // no ID3v1 data found.
                }
            };

            reader.readAsArrayBuffer(this.files[0]);
        };
    </script>*/?>