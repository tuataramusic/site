<?php
/**
 * ��������� �����
 */
#===========================================================================================================================
#�������� 	�������� ��-��������� 	����� 	��������
#useragent	CodeIgniter	���	�������� ������.
#protocol	mail	mail, sendmail, ��� smtp	��������.
#mailpath	/usr/sbin/sendmail	���	��������� ���� � Sendmail.
#smtp_host	�� ����������	���	����� SMTP-�������.
#smtp_user	�� ����������	���	SMTP �����.
#smtp_pass	�� ����������	���	SMTP ������.
#smtp_port	25	���	SMTP ����.
#smtp_timeout	5	���	SMTP ����-��� (� ��������).
#wordwrap	TRUE	TRUE ��� FALSE (boolean)	��������� ���������.
#wrapchars	76		����� �������� �� ��������.
#mailtype	text	text ��� html	��� ������. ���� ��������� ������ � ���� HTML, �� �� ������ ��������� ��� ��� ����������� ���-��������. ���������, ��� ����������� ������������� ������ � ������������� ���� �����������, ����� ��� �� ����� ��������.
#charset	utf-8		��������� ��������� ������ (utf-8, iso-8859-1 � �.�.).
#validate	FALSE	TRUE ��� FALSE (boolean)	��������� email-������.
#priority	3	1, 2, 3, 4, 5	Email ����������. 1 = ����� �������. 5 = ����� ������. 3 = ����������.
#newline	\n	"\r\n" ��� "\n"	��� �������� �� ����� ������. (����������� "\r\n" ��� ���������� ��������� RFC 822).
#bcc_batch_mode	FALSE	TRUE ��� FALSE (boolean)	��������� ��������� ������ BCC.
#bcc_batch_size	200	���	���������� ������� � ����� BCC-������.
#===========================================================================================================================

$config['protocol'] = 'sendmail';
$config['mailpath'] = '/usr/sbin/sendmail';
$config['wordwrap'] = TRUE;
$config['charset']	= 'cp1251';

$config['siteemail']	= 'info@countrypost.ru';
?>