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

<h2><a href="views/products/home.php">Click to HomePage</a></h2>
<!-- <form id="uploadForm" enctype="multipart/form-data">
  <input type="file" name="fileExcel" required><br><br>
  <button type="submit">Upload</button>
</form>

<div id="progressBar"><div>0%</div></div>
<div id="status"></div>

<script>
document.getElementById('uploadForm').addEventListener('submit', function(e) {
  e.preventDefault();

  const formData = new FormData(this);
  const xhr = new XMLHttpRequest();

  xhr.open('POST', 'upload_process.php', true);

  xhr.onloadstart = function () {
  document.getElementById('status').textContent = "Uploading file...";
};

xhr.onload = function () {
  document.getElementById('status').textContent = "Processing file on server...";
  // Khi PHP trả kết quả xong sẽ override status
  setTimeout(() => {
    document.getElementById('status').innerHTML = xhr.responseText;
  }, 500); // Giả lập delay
};

  xhr.upload.onprogress = function (e) {
    if (e.lengthComputable) {
      const percent = Math.round((e.loaded / e.total) * 100);
      const bar = document.querySelector('#progressBar div');
      bar.style.width = percent + '%';
      bar.textContent = percent + '%';
    }
  };

  xhr.send(formData);
});
</script> -->

</body>
</html>
