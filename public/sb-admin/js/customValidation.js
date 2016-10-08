function checkOnlyLitters(field){
    return checkRegEx(field,/^[a-zA-Z .]+$/,"* No special characters or numbers are allowed.");
}
function checkLittersOrNumbers(field){
    return checkRegEx(field,/^[a-zA-Z0-9 .]+$/,"* No special characters are allowed.");
}
function notEqualPrevious(field){
    console.log(field.val() +' - '+  field.prev('input').val() );
    if(field.val() == field.prev('input').val()){
        return "* "+field.attr('name')+" and "+field.prev('input').attr('name')+" must be different";
    }
}
function checkRegEx(field,regex,message){
    var fieldObj = $(field);
    if (fieldObj) {
        if (!regex.test($(fieldObj).val()) ) {
            return message;
        }
    }return true;
}
function datetime(field){
    return checkRegEx(field,/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/,"* Invalid date time formate");
}