var naalaTracking ={
    //Main vars
    serverUrl:null,
    map:null,
    infoWindow:null,
    currentMarkers:[],
    markersCount:3,

    duration:10000,
    icon :'pickup.png',
    //vars for temporary use
    counterOfPosition:0,
    dataForPositions:[],

    //init current run to start tracking
    InitCurrent : function(){
        that = this;
        for (i = 0; i < that.markersCount; i++) {
            console.log( i);
            that.currentMarkers.push( new naalaTrackingMarker({
                position: {lat: 0, lng: 0},
                map: map,
                draggable: false,
                icon: that.icon,
                duration: 1000,
                easing: false,
            }) );
            var counter= i;
            that.currentMarkers[counter].uniqueId=i;
          // that.currentMarkers[counter].initMarker();
        }
        this.listenPosition();

    },

    //start listening for position by run getLocation every duration
    listenPosition:function(){
        thatLoc = this;
        this.getLocation()
        window.setInterval(function(){thatLoc.getLocation()},
            that.duration);
    },
    updatePosition:function(lat,lng){
        var pos = {
            lat: parseFloat(lat),
            lng:parseFloat( lng )
        };
        this.currentMarker.setPosition(pos);
    },

    setLocations:function () {
        that = this;
        that.timeLoop(function(index){
            that.dataForPositions.forEach(
                function(item,positionsIndex){
                    var pos = {
                        lat: parseFloat(item[index][0]),
                        lng: parseFloat(item[index][1])
                    };
                    that.currentMarkers[positionsIndex].setPosition(pos);
                }
            )
        },that.duration/1000 ,1000,0);
    },

    //time Loop function .. run each duration a function until maxCount reached
    timeLoop:function(functionToRun,maxCount,duration,counter){
        that = this;
        if( typeof counter  == 'undefined' )counter=0;
        functionToRun(counter);
        counter+=1;
        if( counter != maxCount){
            setTimeout(function(){
                that.timeLoop(functionToRun,maxCount,duration,counter)
            }  , 1000)
             // repeat myself
        }
    },

    getLocation:function (){
        that=this;
        this.counterOfPosition=0;
        that = this;
        $.ajax({
            url: that.serverUrl,
            dataType:'json',
        }).done(function(data) {

                that.dataForPositions=[];
                data.data.forEach(function(pointsData,index){
                    that.currentMarkers[index].dataForPositions=[];
                    that.currentMarkers[index].dataForPositions= JSON.parse(pointsData.points);
                    that.currentMarkers[index].followPositions(JSON.parse(pointsData.points));
                }.bind(that));
               // that.setLocations();
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

    //follow location
    followLocation:function(){
        that=this;
        this.currentMarker.addListener('click', function() {
            if( this.selected){
                that.followLocation=false;
                this.selected=false ;
                this.icon= this.icon.replace('pickup-selected.png','pickup.png' );
            }else {
                that.followLocation=true;
                this.selected=true ;
                this.icon= this.icon.replace('pickup.png', 'pickup-selected.png');
            }
        });
    }
}
