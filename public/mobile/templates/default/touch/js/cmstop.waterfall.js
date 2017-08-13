(function() {

    var container,
        checkFlag = true,
        gapHeight = 0,
        gapWidth = 5,
        colWidth = 150,
        colArray = [],
        colCount,
        delayer,
        maxcontainer,
        loadLock = false,
        page = 1;

    var getMinVal = function(arr) {
        return Math.min.apply(Math, arr);
    };

    var getMaxVal = function(arr) {
        return Math.max.apply(Math, arr);
    };

    var getMinKey = function(arr) {
        var key = 0, min = arr[0];
        for(var i = 1, len = arr.length; i < len; i++) {
            if(arr[i] < min) {
                key = i;
                min = arr[i];
            }
        }
        return key;
    };

    var getMaxKey = function(arr) {
        var key = 0, max = arr[0];
        for(var i = 1, len = arr.length; i < len; i++) {
            if(arr[i] > max) {
                key = i;
                max = arr[i];
            }
        }
        return key;
    };

    var adjustCells = function(nodes) {
        var colIndex, 
            colHeight;
        for(var j = 0, k = nodes.length; j < k; j++) {
            colIndex = getMinKey(colArray);
            colHeight = colArray[colIndex];
            nodes[j].style.left = colIndex * (colWidth + gapWidth) + 'px';
            nodes[j].style.top = colHeight + 'px';
            colArray[colIndex] = colHeight + gapHeight + nodes[j].offsetHeight;
            nodes[j].className = 'ui-cell ui-ready';
        }
        container.css('height', getMaxVal(colArray));
        checkFlag = true;
        loadCheck();
    };

    var appendCells = function(cols) {
        var pagesize = 10,
            more = true;
        if (loadLock) return;
        loadLock = true;
        $.post('/picture/', {
            width: colWidth,
            page: page,
            pagesize: pagesize
        }, function(json) {
            if (json && json.data && json.data.length) {
                var fragment = document.createDocumentFragment(),
                    cells = [];
                $.each(json.data, function(index, item) {
                    var cell = $('<div class="ui-cell">').html('<a href="'+item.url+'"><img src="'+item.src+'" height="' + item.height + '" /><h3 class="ui-picture-title">'+item.title+'</h3></a>');
                    cells.push(cell[0]);
                    fragment.appendChild(cell[0]);
                });
                container.append(fragment);
                adjustCells(cells);
                more = page * pagesize < (parseInt(json.total) || 0);
                page += 1;
            } else {
                more = false;
            }
            if (!more) {
                $('#loader').hide();
                $(window).off('scroll');
            }
            loadLock = false;
        }, 'json');
    };

    var reflowCheck = function() {
        colCount = Math.floor((maxcontainer.width() + gapWidth) / (colWidth + gapWidth));
        if(colArray.length != colCount) {
            colArray = [];
            container.css('width', colCount * (colWidth + gapWidth) - gapWidth);
            for(var i = 0; i < colCount; i++) {
                colArray.push(gapHeight);
            }
            adjustCells(container[0].childNodes);
        } else {
            loadCheck();
        }
    };

    var loadCheck = function() {
        if(checkFlag && (window.innerHeight || document.documentElement.clientHeight) + (document.documentElement.scrollTop || document.body.scrollTop) > getMinVal(colArray)) {
            checkFlag = false;
            appendCells(colCount);
        }
    };

    var reflowDelay = function() {
        clearTimeout(delayer);
        delayer = setTimeout(reflowCheck, 500);
    };

    var init = function() {
        container.css('width', colCount * (colWidth + gapWidth) - gapWidth);
        for(var i = 0; i < colCount; i++) {
            colArray.push(gapHeight);
        }
        loadCheck();
    };


    $(function() {
        container = $('.js-waterfall');
        maxcontainer = $('.ui-container');
        colCount = Math.floor((maxcontainer.width() + gapWidth) / (colWidth + gapWidth));
        $(window)
            .on('scroll', function() {
                loadCheck();
            })
            .on('resize', function() {
                reflowDelay();
            });
        init(); 
    });

})();