(function () {

    var encode = encodeURIComponent;
    var decode = decodeURIComponent;

    window.cookie = {
        get: function (name) {
            assertValidateName(name);
            var cookies = parseCookie();
            return cookies[name];
        },
        set: function (name, value, options) {
            assertValidateName(name);
            options || (options = {});

            var expires = options.expires;
            var domain = options.domain;
            var path = options.path;
            var cookie = encode(name) + '=' + encode(value);

            var date = expires;
            if (typeof date === 'number') {
                date = new Date();
                date.setDate(date.getDate() + expires);
            }
            if (date && date instanceof Date) {
                cookie += '; expires=' + date.toUTCString();
            }

            if (!empty(domain)) {
                cookie += '; domain=' + domain;
            }

            if (!empty(path)) {
                cookie += '; path=' + path;
            }

            if (options['secure']) {
                cookie += '; secure';
            }

            document.cookie = cookie;
            return cookie;
        },
        remove: function (name, options) {
            assertValidateName(name);
            options || (options = {});
            options['expires'] = new Date(0);
            return this.set(name, '', options);
        }
    };

    function empty(str){
        return typeof str !== 'string' || str === '';
    }

    function assertValidateName(name) {
        if (empty(name)) {
            throw new TypeError('cookie name must be a non-empty string');
        }
    }

    function parseCookie() {
        var parts = document.cookie.split(/;\s/g),
            result = {}, pairs;
        while ((pairs = parts.shift())) {
            pairs = pairs.match(/([^=]+)(?:=(.*))?/);
            result[decode(pairs[1])] = empty(parts[2]) ? '' : decode(pairs[2]);
        }
        return result;
    }

})();
