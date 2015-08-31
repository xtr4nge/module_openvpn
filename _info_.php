<?
$mod_name="openvpn";
$mod_version="1.0";
$mod_path="/usr/share/fruitywifi/www/modules/$mod_name";
$mod_logs="$log_path/$mod_name.log"; 
$mod_logs_history="$mod_path/includes/logs/";
$mod_panel="show";
$mod_type="service";
$mod_alias="OpenVPN";

# ISUP
$mod_isup="ps aux|grep -E 'openvpn --config' | grep -v grep | awk '{print $2}'";

# EXEC
$bin_sudo = "/usr/bin/sudo";
$bin_openvpn = "/usr/sbin/openvpn";
$bin_chown = "/bin/chown";

$bin_python = "/usr/bin/python";
$bin_ifconfig = "/sbin/ifconfig";
$bin_iwlist = "/sbin/iwlist";
$bin_sh = "/bin/sh";
$bin_echo = "/bin/echo";
$bin_grep = "/usr/bin/ngrep";
$bin_killall = "/usr/bin/killall";
$bin_cp = "/bin/cp";
$bin_chmod = "/bin/chmod";
$bin_sed = "/bin/sed";
$bin_rm = "/bin/rm";
$bin_route = "/sbin/route";
$bin_perl = "/usr/bin/perl";
$bin_sleep = "/bin/sleep";

?>
