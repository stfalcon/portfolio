$(function () {
    var waypoint = new Waypoint({
        element: document.getElementById('element-waypoint'),
        handler: function (direction) {
            if (direction === 'down') {
                alert(this.element.id + ' triggers at ' + this.triggerPoint);
            }
        },
        offset: '95%'
    })
});