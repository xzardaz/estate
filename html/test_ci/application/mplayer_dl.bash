#!/bin/sh
#
# Public domain
# Author: roman [] tsisyk.com
#
# Usage: ./me url [youtube-dl parameters]
#

COOKIE_FILE=/var/tmp/youtube-dl-cookies.txt
#mplayer -cookies -cookies-file ${COOKIE_FILE} \""ffmpeg://"$(youtube-dl -g --cookies ${COOKIE_FILE} $*)\"

mplayer "ffmpeg://"$(youtube-dl -g --cookies ${COOKIE_FILE} $*)
#echo \"$(youtube-dl -g --cookies ${COOKIE_FILE} $*)\"
