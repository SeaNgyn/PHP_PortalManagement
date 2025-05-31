<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Upload Excel with Progress</title>
    <style>
        #progressBar {
            width: 100%;
            background: #f3f3f3;
            border: 1px solid #ccc;
            margin-top: 10px;
        }

        #progressBar div {
            height: 20px;
            width: 0%;
            background-color: green;
            text-align: center;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container" style="max-width: 500px; margin-top: 50px;">
        <div class="panel panel-primary">
            <div class="panel-heading" style="height:auto;background-color:blue">
                <h3 class="panel-title" style="font-size: 20px; font-family: Helvetica; color: white;">Chọn thời khoá biểu</h3>
            </div>
            <div class="panel-body" style="height: 165px;">
                <!-- <form action="upload_file.php" method="post" enctype="multipart/form-data" class="form-horizontal"> -->
                <form id="uploadForm" enctype="multipart/form-data" class="form-horizontal">
                    <div class="form-group">
                        <label for="semester" class="col-sm-3 control-label">Học kỳ</label>
                        <div class="col-sm-9" style="margin-top: 10px;">
                            <select name="semester" id="semester" class="form-control" required>
                                <option value="">-- Chọn học kỳ --</option>
                                <option value="1">Học kỳ 1</option>
                                <option value="2">Học kỳ 2</option>
                                <!-- <option value="3">Học kỳ Hè</option> -->
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="myfile" class="col-sm-3 control-label">Chọn file</label>
                        <div class="col-sm-9">
                            <!-- <input type="file" name="myfile" id="myfile" class="form-control" required style="margin-top: 15px;"> -->
                            <input type="file" name="fileExcel" accept=".xlsx,.xls" class="form-control" style="margin-top: 15px;" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" class="btn btn-success" style="margin-top: 15px;">Tải lên</button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="progressBar">
                <div>0%</div>
            </div>
            <div id="status"></div>
        </div>
    </div>


    <script>
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../../upload_process.php', true);

            xhr.onloadstart = function() {
                document.getElementById('status').textContent = "Uploading file...";
            };

            xhr.onload = function() {
                document.getElementById('status').textContent = "Processing file on server...";
                // Khi PHP trả kết quả xong sẽ override status
                setTimeout(() => {
                    document.getElementById('status').innerHTML = xhr.responseText;
                }, 500); // Giả lập delay
            };

            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    const bar = document.querySelector('#progressBar div');
                    bar.style.width = percent + '%';
                    bar.textContent = percent + '%';
                }
            };

            xhr.send(formData);
        });
    </script>
</body>

</html>