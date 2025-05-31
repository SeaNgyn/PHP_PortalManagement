function searchCourse() {
    const inputCode = document.getElementById('searchCode').value.trim().toUpperCase();
    const table = document.getElementById('courseTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    let found = false;
  
    for (let i = 0; i < rows.length; i++) {
      const codeCell = rows[i].getElementsByTagName('td')[1];
      if (codeCell && codeCell.innerText.trim().toUpperCase() === inputCode) {
        rows[i].style.backgroundColor = '#c8e6c9'; // tô màu nếu tìm thấy
        document.getElementById('result').innerText = 'Đã tìm thấy học phần: ' + rows[i].innerText;
        found = true;
      } else {
        rows[i].style.backgroundColor = ''; // bỏ màu nếu không khớp
      }
    }
  
    if (!found) {
      document.getElementById('result').innerText = 'Không tìm thấy học phần có mã: ' + inputCode;
    }
  }