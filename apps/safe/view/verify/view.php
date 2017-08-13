<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?=$head['title']?></title>

    <link rel="stylesheet" type="text/css" href="css/admin.css"/>
    <script type="text/javascript" src="http://img.convert.loc/js/lib/jquery.js"></script>
    <script type="text/javascript" src="http://img.convert.loc/js/config.js"></script>
    <script type="text/javascript" src="http://img.convert.loc/js/cmstop.js"></script>
    <script type="text/javascript">
        $(function(){
            var rowid = "<?php echo $rowid;?>";
            var pw = window.parent.document;
            var filename = $('#row_' + rowid, pw).find('.filename').html();
            $.ajax(
                {
                    url:"?app=safe&controller=verify&action=view",
                    type:'POST',
                    dataType:'json',
                    data:'filename=' + filename,
                    success:function(json)
                    {
                        if(json.state)
                        {
                            $("textarea[name='code']").val(json.data);
                        }
                        else
                        {
                            $("textarea[name='code']").val(json.error);
                        }
                    }
                }
            );
        })
    </script>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center"><textarea name="code" style="width:95%;height:480px;" readonly="readonly"></textarea></td>
    </tr>
</table>
</body>
</html>