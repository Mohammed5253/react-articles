export const setModal = (data) =>{
    
    return dispatch =>{
         return dispatch({
            type:"SHOW_MODAL"
        })
    };    
}

export const resetModal = (data) =>{
   
    return dispatch =>{
         return dispatch({
            type:"HIDE_MODAL"
        })
    };    
}

export const loginModal = (data) =>{
    
    return dispatch =>{
        return dispatch({
           type:"LOGIN_MODAL"
       })
   };  
}

export const showPostModal = (data) => {
    return dispatch =>{
        return dispatch({
            type:"SHOW_POST_MODAL"
        })
    }
}

export const addImages = (data) => {
    console.log(data);
    return dispatch => {
        return dispatch({
            type:"ADD_IMAGES",
            data:data
        })
    }

}