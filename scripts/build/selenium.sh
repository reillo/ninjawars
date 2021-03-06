#!/bin/bash
# This script is used to control selenium service
#
# Run it :
# bash scripts/build/selenium.sh (start|stop|restart)

# Include functions
_DIR_="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
source $_DIR_/functions.sh

# Used selenium-server
SELENIUM="selenium-server-standalone-2.33.0.jar"

say_loud "Preparing..." "SELENIUM"
ensure_selenium

# See current process
SELENIUM_PID="0"
SELENIUM_STARTED=$(ps aux | grep "java -jar /usr/lib/selenium/$SELENIUM")
if [ "" == "$SELENIUM_STARTED" ]; then
	SELENIUM_PID="0"
else
	ACTIVE=$(echo $SELENIUM_STARTED | awk '{split($0,array," ")} END{print array[8]}')
	if [ "S" == "$ACTIVE" ] || [ "Sl" == "$ACTIVE" ] || [ "S+" == "$ACTIVE" ] || [ "Sl+" == "$ACTIVE" ]; then
		NOTCURRENT=$(echo $SELENIUM_STARTED | awk '{split($0,array," ")} END{print array[11]}')
		if [ "grep" != "$NOTCURRENT" ]; then
			SELENIUM_PID=$(echo $SELENIUM_STARTED | awk '{split($0,array," ")} END{print array[2]}')
		fi
	fi;
fi

# Preparing any necessary files/folder
sudo mkdir -p /var/log/selenium
sudo touch /var/log/selenium/selenium-output.log
sudo touch /var/log/selenium/selenium-error.log
sudo touch /tmp/selenium.pid
sudo chmod 777 /var/log/selenium/selenium-output.log
sudo chmod 777 /var/log/selenium/selenium-error.log
sudo chmod 777 /tmp/selenium.pid

case "${1:-''}" in
	'start')
		say_loud "Starting Selenium..." "SELENIUM"

		if [ "0" != $SELENIUM_PID ]
			then
			say_warning "Selenium is already running." "SELENIUM"
		else
			xvfb-run --auto-servernum java -jar /usr/lib/selenium/$SELENIUM > /var/log/selenium/selenium-output.log 2> /var/log/selenium/selenium-error.log & echo $! > /tmp/selenium.pid

			error=$?
			if test $error -gt 0
				then
				say_error "${bon}Error $error! Couldn't start Selenium!${boff}" "SELENIUM"
			else
				say_ok "Started" "SELENIUM"
			fi
		fi
	;;
	'stop')
		say_loud "Stopping Selenium..." "SELENIUM"
		if [ "0" != $SELENIUM_PID ]
		then
			kill -3 $SELENIUM_PID
			if kill -9 $SELENIUM_PID;
				then
				sleep 2
				say_ok "Stopped" "SELENIUM"
			else
				say_error "Selenium could not be stopped..." "SELENIUM"
			fi
		else
			say_warning "Selenium is not running." "SELENIUM"
		fi
		;;
	'restart')
		say_loud "Restarting Selenium..." "SELENIUM"
		if [ "0" != $SELENIUM_PID ]
			then
			say_info "Stopping..." "SELENIUM"
			kill -HUP $SELENIUM_PID
			sleep 1
			xvfb-run --auto-servernum java -jar /usr/lib/selenium/$SELENIUM > /var/log/selenium/selenium-output.log 2> /var/log/selenium/selenium-error.log & echo $! > /tmp/selenium.pid
			say_ok "Restarted" "SELENIUM"
		else
			say_info "Selenium isn't running..." "SELENIUM"
			xvfb-run --auto-servernum java -jar /usr/lib/selenium/$SELENIUM > /var/log/selenium/selenium-output.log 2> /var/log/selenium/selenium-error.log & echo $! > /tmp/selenium.pid
			say_ok "Restarted" "SELENIUM"
		fi
		;;
	*)  # no parameter specified
		say_loud "Usage:
		bash $0 start|stop|restart" "SELENIUM"
		exit 1
	;;
esac
