const initialState = {
    modalType: null,
    modalProps: {},
    show:false,
    loginshow:false,
    showpostmodal:false,
    imagearray:[],
    articledata:'',
    imagePreviewUrl:[]
  }

export const custreducers = (state = initialState, action) => {
    console.log(state.imagePreviewUrl.push('dd'),action)
    switch (action.type) {
        case 'SHOW_MODAL':
          return {
            show:true
          }
        case 'LOGIN_MODAL':
          return {
            loginshow:true
          }  
        case 'SHOW_POST_MODAL':
          return {
            showpostmodal:true
          }
        case 'ADD_IMAGES':
          return {
            ...state,
            imagePreviewUrl:state.imagePreviewUrl.concat()
          }    
        case 'HIDE_MODAL':
          return initialState
        default:
          return state
      }

}


export default custreducers;