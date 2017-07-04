var util = require('util');
var events = require('events');

function WaitForPageToLoad() {
    events.EventEmitter.call(this);
}

util.inherits(WaitForPageToLoad, events.EventEmitter);

WaitForPageToLoad.prototype.command = function command(timeout) {
    this._stackTrace = command.stackTrace;
    this.checkInterval = 200;
    this.timeout = timeout || 10000;
    this.started = (new Date()).getTime();
    this.check();
    return this;
};

WaitForPageToLoad.prototype.check = function() {
    // console.log('check');
    this.client.api.execute(function(data) {
        // Executed in the client
        return !window.isLoading || window.isLoading();
    }, [], this.handleResult.bind(this));
    return this;
};

WaitForPageToLoad.prototype.handleResult = function(result) {
    // console.log('handleResult');
    if (!result.value) {
        return this.complete();
    } else if ((new Date()).getTime() - this.started > this.timeout) {
        return this.fail();
    } else {
        setTimeout(this.check.bind(this), this.checkInterval);
        return this;
    }
};

WaitForPageToLoad.prototype.fail = function() {
    // console.log('fail');
    this.client.assertion(false,
        'page still loading',
        'page loaded in less than ' + this.timeout + 'ms',
        'timeout', true, this._stackTrace);
    return this.complete();
};

WaitForPageToLoad.prototype.complete = function() {
    // console.log('complete');
    this.emit('complete');
    return this;
};

module.exports = WaitForPageToLoad;
