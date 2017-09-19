#!/usr/bin/expect
set timeout 3
spawn telnet 192.168.113.254
expect "username:"
send "xmg\r"
expect "password:"
send "xmg175207\r"
send "sys\r"
send "acl 3307\r"
send "undo rule 13\r"
send "undo rule 14\r"
send "undo rule 15\r"
send "rule 13 permit ip source 192.168.7.0 0.0.0.127\r"
send "rule 14 permit ip source 192.168.7.64 0.0.0.191\r"
send "rule 15 permit ip source 192.168.7.32 0.0.0.223\r"
send "q\r"

send "q\r"
send "save\r"
expect "Are you sure to continue?\[Y/N\]"
send "y\r"
send "q\r"
expect eof
