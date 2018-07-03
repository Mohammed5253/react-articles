import React, {Component} from 'react';
import {connect} from 'react-redux';

import { Modal} from 'antd';
import { Icon } from 'semantic-ui-react'
import * as action from '../../_actions/actions'

class PostModal extends Component {
  ontextareaChange = (e) => {
    console.log(e.target.value);
  }
  //searchinfluncer
  onChangeFile = (event) => {
    event.preventDefault();
    let reader = new FileReader();
    let files = event.target.files;
    var index;
    for(index = 0;index<files.length;index++){
      var file = files[index];
      this.handleLoadImage(file);
    }
    console.log(file);
    this.setState({file}); /// if you want to upload latter
  }
  handleLoadImage = (file) => {
   if (file) {
     
      const reader = new FileReader();
      reader.onloadend = () => {
      this.props.addImages({type:"ADD_IMAGES",data:reader.result})
      }
          // this.setState(() => ({
          //   imagePreviewUrl: this.state.imagePreviewUrl.concat([reader.result]),
          // }));
        
      reader.readAsDataURL(file);
      
    }
  }
    render(){
       console.log(this.props)
      //  const imgpre =
      //  this.state.imagePreviewUrl.length>0 ?
      //  (
      //    <div>
      //    {this.state.imagePreviewUrl.map((imgurl)=>
         
      //          <div style={{display:"inine-block",float:"left"}}> 
      //          <img src={imgurl} width="70xp" height="70px" multiple className="img-rounded" /> 
      //          <Icon  className="times" id="removeImg"></Icon> 
      //      </div>
      //      )}
      //      <span style={ {display:"block",
      //        width:"70px", 
      //        height:"70px",
      //        border:"dashed 1px #c3bdbd", 
      //        textAlign:"center",
      //        borderRadius:"10px",
      //        float:"left",
      //        fontStyle:" normal",} } onClick={()=>{this.upload.click()}}>  </span>
      //      </div>
      //  ):(null);     
     return (
        <Modal
        title="Create An Article"
        visible={this.props.status}
        onOk={this.props.handleCancel}
        onCancel={this.props.handleCancel}
        className="articleWidth"
      >
       <textarea cols="105" 
                className="userautoscrptedit"
                onChange={this.ontextareaChange.bind(this)}
        >

       </textarea>
       <span>
          <i style={{color:"rgb(150, 147, 147)",position:"relative",bottom:"71px",fontSize: "30px"}} 
            className="pull-right" onClick={()=>{this.upload.click()}}>
            <Icon name='camera' />
          </i>
        </span>
        {/* {imgpre} */}
        <input id="myInput"

          type="file"
          ref={(ref) => this.upload = ref}
          style={{display: 'none'}}
          onChange={this.onChangeFile.bind(this)} 
          multiple
        /> 
      </Modal>
     )  
    }
}

export default connect('',action)(PostModal);