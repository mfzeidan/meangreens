
	
def mail_to_send(temp):	
	import smtplib
	from email.MIMEMultipart import MIMEMultipart
	from email.MIMEText import MIMEText
	 
	 
	fromaddr = "smokertempmail@gmail.com"
	toaddr = "mfzeidan@gmail.com"
	msg = MIMEMultipart()
	msg['From'] = fromaddr
	msg['To'] = toaddr
	msg['Subject'] = "SUBJECT OF THE MAIL"
	 
	body = "Your smoker has reached the desire temperature of " + str(temp) + " please remove it now!"
	msg.attach(MIMEText(body, 'plain'))
	 
	server = smtplib.SMTP('smtp.gmail.com:587')
	server.ehlo()
	server.starttls()
	server.login(fromaddr, "44sdsdsdSD")
	text = msg.as_string()
	server.sendmail(fromaddr, toaddr, text)
	server.quit()
	
mail_to_send(70)