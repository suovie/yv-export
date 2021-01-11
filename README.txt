yv-export - a quick PHP script to export your YouVersion Bible.com data to JSON

HOW TO USE:
1. Grab cookies from web browser's active session and save to file.
2. Pass cookie file as first argument
3. Pass username as second argument
4. Pass data type as third argument, default is 'activity'

Data types include: activity, highlight, note, bookmark

Sample command:
php export.php ./cookie.txt [username] note

Redacted cookie sample extracted from Google Chrome:

__cfduid={random}; locale=en; _youversion-web_session={random};
yvid={your_id}; cc={random}; auth_type=email; YouVersionToken2={JWT Token};
OAUTH={JWT Token}; LocalOnceDaily::DailyStreakCheckin-{your_id}={date};
alt_version=1; last_read={last passage read}

Exported data will be saved in "./data" directory