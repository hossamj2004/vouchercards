var naalaTracking ={
    //Main vars
    serverUrl:null,
    map:null,
    infoWindow:null,
    currentMarker:null,
    duration:20000,
    clientDuration:20000,
    icon :'pickup.png',
    lastServerTime:null,
    timeIntervalKey:null,
    isLocationDataValid:function(data){
        that=this;
        if( ! ( that.isset( data.data) &&
            that.isset( data.data.points) &&
            that.isJsonString( data.data.points ) ) )
        {
            console.log('Invalid data');
            return false ;
        }
        if(that.lastServerTime !=null  && that.lastServerTime == data.data.currentTime )
            console.log( 'time repeated'+ that.lastServerTime );
        that.lastServerTime = data.data.currentTime;
        console.log( that.getDate(data.serverTime) - that.getDate(data.data.currentTime) );
        if(! ( that.isset( data.data.currentTime ) &&
            ( that.getDate(data.serverTime) - that.getDate(data.data.currentTime) ) <= that.duration ) )
        {
            console.log( 'out' );
            return false ;
        }
        return true;
    },


    //init current run to start tracking
    InitCurrent : function(){
        that = this ;
        this.currentMarker=  new naalaTrackingMarker({
            position: {lat: 0, lng: 0},
            map: map,
            draggable: false,
            icon: this.icon,
            duration: 1000,
            easing: false,
            selected:false,

        });
        this.listenPosition();
        //follow location
        this.currentMarker.initMarker();
    },

    //start listening for position by run getLocation every duration
    listenPosition:function(){
        that = this;
        this.getLocation()
        that.timeIntervalKey = window.setInterval(function(){naalaTracking.getLocation();},
            naalaTracking.duration);
    } ,


    stopListening :function(){
        clearInterval(this.timeIntervalKey);
    },

    stopCurrent: function(){
        this.currentMarker.setMap(null);
        this.stopListening();
    },

    getLocation:function (){
        this.counterOfPosition=0;
        that = this;
        $.ajax({
            url: that.serverUrl,
            dataType:'json',
        }).done(function(data) {
            //check if time of request have passed duration i consider it inaction
            if( naalaTracking.isLocationDataValid(data) )
            {
                that.currentMarker.setOpacity(1);
                if(!that.currentMarker.getVisible())that.currentMarker.setVisible(true);
                that.currentMarker.dataForPositions= JSON.parse(data.data.points);
                if( that.duration == that.clientDuration )
                    that.currentMarker.followPositions();
                else
                    that.currentMarker.jumpToLastPoint();
            }else {
                that.currentMarker.setOpacity(0.5);
            }
        });
    },

    getDate:function (date){
        return new Date(date.replace(/-/g,"/"));
    },

    getCurrentPosition:function(callback){
        var pos = {
            lat: 0,
            lng: 0
        };
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                callback(pos);
            }, function() {
                console.log("cant find current position");
            });
        }else {
            console.log("cant find current position");
        }
        //callback(pos);
    },

    //misc functions
    isJsonString:function(str){
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    },
    isset:function (variable){
        return typeof variable !=="undefined"
    },
}
