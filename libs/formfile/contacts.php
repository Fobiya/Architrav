<?php
if (isset ($_POST['contactFF'])) {
  $to = "name@yandex.ru"; // �������� �� ���� ����������� �����
  $from = $_POST['contactFF'];
  $subject = "��������� ���������� ����� � ".$_SERVER['HTTP_REFERER'];
  $message = "���: ".$_POST['nameFF']."\nEmail: ".$from."\nIP: ".$_SERVER['REMOTE_ADDR']."\n���������: ".$_POST['messageFF'];
  $boundary = md5(date('r', time()));
  $filesize = '';
  $headers = "MIME-Version: 1.0\r\n";
  $headers .= "From: " . $from . "\r\n";
  $headers .= "Reply-To: " . $from . "\r\n";
  $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
  $message="
Content-Type: multipart/mixed; boundary=\"$boundary\"

--$boundary
Content-Type: text/plain; charset=\"utf-8\"
Content-Transfer-Encoding: 7bit

$message";
  for($i=0;$i<count($_FILES['fileFF']['name']);$i++) {
     if(is_uploaded_file($_FILES['fileFF']['tmp_name'][$i])) {
         $attachment = chunk_split(base64_encode(file_get_contents($_FILES['fileFF']['tmp_name'][$i])));
         $filename = $_FILES['fileFF']['name'][$i];
         $filetype = $_FILES['fileFF']['type'][$i];
         $filesize += $_FILES['fileFF']['size'][$i];
         $message.="

--$boundary
Content-Type: \"$filetype\"; name=\"$filename\"
Content-Transfer-Encoding: base64
Content-Disposition: attachment; filename=\"$filename\"

$attachment";
     }
   }
   $message.="
--$boundary--";

  if ($filesize < 10000000) { // �������� �� ����� ������ ���� ������. ������ �������� ������� �� ��������� �������� ������ 10 ��
    mail($to, $subject, $message, $headers);
    echo $_POST['nameFF'].', ���� ��������� ��������, �������!';
  } else {
    echo '��������, ������ �� ����������. ������ ���� ������ ��������� 10 ��.';
  }
}
?>