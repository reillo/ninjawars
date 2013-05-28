#!/bin/bash
# This script is used to prepare and run the test
#
# Run it :
# bash scripts/build/test.sh

# Include functions
_DIR_="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
source $_DIR_/functions.sh
echo $0
exit

say_loud "Preparing..." "TEST"
bash $_DIR_/selenium.sh start

# Idle
say_loud "Waiting... [20 seconds]" "TEST"
sleep 5 
say_info "Waiting... [15 seconds]" "TEST"
sleep 5 
say_info "Waiting... [10 seconds]" "TEST"
sleep 5 
say_info "Waiting... [5 seconds]" "TEST"
sleep 5 

# Run
say_info "Assuming finished." "TEST"
say_loud "Running test-suite" "TEST"
vendor/bin/phpunit

# Clean up
say_loud "Cleaning up..." "TEST"

# Idle
say_loud "Waiting... [20 seconds]" "TEST"
sleep 5 
say_info "Waiting... [15 seconds]" "TEST"
sleep 5 
say_info "Waiting... [10 seconds]" "TEST"
sleep 5 
say_info "Waiting... [5 seconds]" "TEST"
sleep 5 

# Close selenium
bash $_DIR_/selenium.sh stop
say_ok "Completed!" "TEST"