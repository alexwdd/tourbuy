/** layuiAdmin.std-v1.2.1 LPPL License By http://www.layui.com/admin/ */
 ;function checkMobile(t){var n=/^1[3|4|5|6|7|8|9][0-9]{9}$/;return flag=n.test(t),!!flag}function checkUrl(t){var n="^((https|http|ftp|rtsp|mms)://)?[a-z0-9A-Z]{0,20}.[a-z0-9A-Z][a-z0-9A-Z]{0,61}?[a-z0-9A-Z].com|net|cn|cc (:s[0-9]{1-4})?/$",e=new RegExp(n);return!!e.test(t)}function checkEmail(t){var n=/^([a-zA-Z0-9_-])+(\.([a-zA-Z0-9_-])+)*@([a-zA-Z0-9_-])+(\.([a-zA-Z0-9_-])+)+$/;return flag=n.test(t),!!flag}function checkWordLong(t,n,e){return t.length>=n&&t.length<=e}