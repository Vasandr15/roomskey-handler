import axios from "axios";
import {baseURL} from "../consts/baseURL.js";

export const registerUser = (data) =>{
    axios.post(`${baseURL}/user/register`,{data})
        .then(res=>{

        })
}
