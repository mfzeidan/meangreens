
import smtplib
from email.MIMEMultipart import MIMEMultipart
from email.MIMEText import MIMEText


def mail_to_send(to_email, subject, body):


	from_email = "smokertempmail@gmail.com"
	fromaddr = from_email
	toaddr = to_email
	msg = MIMEMultipart()
	msg['From'] = from_email
	msg['To'] = to_email

	msg['Subject'] = subject
	 
	msg.attach(MIMEText(body, 'plain'))
	 
	server = smtplib.SMTP('smtp.gmail.com:587')
	server.ehlo()
	server.starttls()

	server.login(fromaddr, '.....')
	text = msg.as_string()
	server.sendmail(from_email, to_email, text)
	server.quit()
	
	
#mail_to_send(70)





