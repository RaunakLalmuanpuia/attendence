
import { date } from 'quasar'

export default function (){

    const formatTime=val=>{
        const options = {
            hour: "numeric",
            minute: "numeric",
            second: "numeric",
            timeZone: "India/Calcutta",
            timeZoneName: "short",
        };
        return (new Intl.DateTimeFormat("en-IN", options).format(val));
    }
    const formatSignoutTime=val=>{
        return date.formatDate(new Date(val),'hh:mm a')
    }
    const formatDateTime=val=>{
        return date.formatDate(new Date(val),'DD-MM-YYYY hh:mm a')
    }
    const APIRESPONSE= Object.freeze({
         INVALID_GEO : 444,
         ALREADY_SIGNIN :441,
         SUCCESS :200,
    })
    const MAP_KEY = "AIzaSyAfJe79QT1KewiB2ghYgX-fPHyCYUxh6d4";
    return{
        formatTime,
        formatSignoutTime,
        formatDateTime,
        APIRESPONSE,
        MAP_KEY
    }

}
