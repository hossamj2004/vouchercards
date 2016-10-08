var naalaTrackingMarker =SlidingMarker;

//on create naalaTrackingMarker run this
naalaTrackingMarker.prototype.initMarker=function(){
    if( this.enableClickToFollow)
        this.followMarkerOnClick();
}
/*
  follow location
 */
naalaTrackingMarker.prototype.enableClickToFollow=true;
naalaTrackingMarker.prototype.followLocation=false;
naalaTrackingMarker.prototype.uniqueId=0;
naalaTrackingMarker.prototype.followMarkerOnClick=function(){
    that=this;
    this.addListener('click', function() {
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
};
naalaTrackingMarker.prototype.updateCenter=function () {
    if ( this.followLocation &&
        !this.map.getBounds().contains( this.getPosition()) )
        this.map.panTo( this.getPosition() );
};
/*
    follow group of points of points
 */
naalaTrackingMarker.prototype.dataForPositions=[];
naalaTrackingMarker.prototype.counterOfPosition=0;

naalaTrackingMarker.prototype.followPositions= function () {
    this.timeLoop(function(index){
        var points = this.dataForPositions[index];
        this.updatePosition(points[0],points[1] );
    }.bind(this),this.dataForPositions.length ,1000,0);
};


naalaTrackingMarker.prototype.jumpToLastPoint=function(){
    that=this;
    var lastPoint= that.dataForPositions[that.dataForPositions.length - 1];
    var pos = {
        lat: parseFloat( lastPoint[0] ),
        lng: parseFloat( lastPoint[1] )
    };
    this.currentMarker.setPosition(pos);
};


naalaTrackingMarker.prototype.updatePosition=function(lat,lng){
    var pos = {
        lat: parseFloat(lat),
        lng: parseFloat( lng )
    };
    this.setPosition(pos);
    this.updateCenter();
};

var uniqueVariable ;
//time Loop function .. run each duration a function until maxCount reached
naalaTrackingMarker.prototype.timeLoop=function(functionToRun,maxCount,duration,counter){
    thatNaalaTrackingMarker = this;
    if( typeof counter  == 'undefined' )counter=0;
    functionToRun(counter);
    counter+=1;
    if( counter != maxCount){
        setTimeout(function(){
            thatNaalaTrackingMarker.timeLoop(functionToRun,maxCount,duration,counter)
        }  , 1000); // repeat myself
    }
};