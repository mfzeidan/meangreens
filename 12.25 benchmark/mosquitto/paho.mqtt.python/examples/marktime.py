from datetime import datetime
from pytz import timezone

fmt = "%Y-%m-%d %H:%M:%S"

# Current time in UTC
now_utc = datetime.now(timezone('UTC'))
# Convert to US/Pacific time zone
now_est = now_utc.astimezone(timezone('US/Eastern'))
print now_est.strftime(fmt)