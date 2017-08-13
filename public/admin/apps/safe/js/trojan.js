$(function(){
    $(".table_head .update_trojan").bind("click", function(){
        $.getJSON("?app=safe&controller=trojan&action=update_trojan", function(json){
            if (json.state) {
                var info = '本地木马库最后更新时间：<span id="detecttime">' + json.lasttime+ '</span>&nbsp;&nbsp;';
                $(".trojan_version").html(info);
                ct.ok(json.message);
                if (json.maxid) {
                    trojan_version = json.maxid;
                }
            } else {
                ct.error(json.error);
            }
        }, "json");
    });

    var trojan_row_template =
        '<tr>' +
            '<td class="t_c">{number}</td>' +
            '<td>{file}</td>' +
            '<td>{reason}</td>' +
            '<td class="t_c">{code}</td>' +
            '<td class="t_c">{filemtime}</td>' +
        '</tr>';
    var trojanScan = function(){
        var scanbox = $(".scan-box");
        var close, progressControl, progress, percent, indicator, control, state, output;
        scanbox.find('div').each(function(){
            switch(this.className){
                case 'close': close = $(this); break;
                case 'progress-control': progressControl = $(this); break;
                case 'progress': progress = $(this); break;
                case 'percent': percent = $(this); break;
                case 'indicator': indicator = $(this); break;
                case 'current': current = $(this); break;
            }
        });
        output = $(".output");
        control = $(".control");
        var running = false, proceed = 0, errors = 0, ival = null, inPing = true, xhr, lastTime;
        progress.hide();
        function stop(clear){
            running = false;
            progress.hide();
            progressControl.removeClass('wide');
            xhr && xhr.abort();
            control.html('重新扫描');
            current.html('共扫描：'+proceed+'文件，<span style="color:red">可疑项：'+errors+'</span>');
            if (lastTime) {
                $(".last-scan").html('上次扫描时间：' + lastTime);
            }
            ival && clearTimeout(ival);
            $.getJSON('?app=safe&controller=trojan&action=stopTest&clear='+(clear||0));
            ct.endLoading();
        }
        function start(restart){
            if (inPing) return;
            running = true;
            proceed = 0;
            errors = 0;
            percent.html('0%');
            indicator.width('0%');
            progressControl.addClass('wide');
            progress.show();
            scanbox.show();
            control.html('终止扫描');
            current.addClass('wide');
            current.empty();
            output.empty();
            var data = [];
            $("input[name='exclude']:checked").each(function(i){
                data[i] = $(this).val();
            });
            xhr = $.ajax({
                dataType:'json',
                data: "restart=" + (restart || 0) +"&exclude=" + data.join(","),
                url:'?app=safe&controller=trojan&action=scan',
                success: function(json) {
                if (!json.state) {
                    stop();
                }
            }
            });
            ival = setTimeout(ping, 50);
        }
        function update(json){
            inPing = false;
            if (!running) return;
            if (json.state) {
                xhr.abort();
                var p = Math.floor(json.percent * 100)+'%';
                percent.html(p);
                indicator.width(p);
                proceed = json.proceed;
                lastTime = json.lasttime;
                current.html('正在扫描:'+json.current);
                if (json.results && json.results.length) {
                    var file_num = json.results.length;
                    for(var i = 0; i < file_num; i++) {
                        var cur_num = json.results[i].length;
                        errors += cur_num;
                        for(var j = 0; j < cur_num; j++) {
                            var one_trojan = '';
                            one_trojan = trojan_row_template;
                            for(var key in json.results[i][j]) {
                                one_trojan = one_trojan.replace(new RegExp("{"+key+"}"), json.results[i][j][key]);
                            }
                            one_trojan = one_trojan.replace(new RegExp("{number}"), $(".output tr").length + 1);
                            $(".output").append(one_trojan);
                        }
                    }
                }
                if (json.percent == 1 || json.total == 0) {
                    return stop();
                }
            } else if (proceed > 0) {
                return stop();
            }
            ival = setTimeout(ping, 300);
        }
        function ping(){
            inPing = true;
            $.getJSON('?app=safe&controller=trojan&action=pingTest&proceed='+proceed, update);
            ct.endLoading();
        }
        control.click(function(){
            if (trojan_version == '') {
                ct.warn('未下载云端木马库，请点击右侧的更新按钮');
            } else {
                running ? stop(1) : start(1);
            }
        });
        if (trojan_version) {
            $.getJSON('?app=safe&controller=trojan&action=pingTest', function(json){
                inPing = false;
                json.state && start();
            });
        } else {
            inPing = false;
        }
    }
    trojanScan();
});