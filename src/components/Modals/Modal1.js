import React, {Component} from 'react';

import { Modal } from 'antd';
// import * as action from '../_actions/actions'
// import ModalRoot from '../components/ModalRoot';


class Modal1 extends Component {
    render(){
      console.log("DD")
     return (
        <Modal
        title="Basic Modal"
        visible={this.props.status}
        onOk={this.props.handleCancel}
        onCancel={this.props.handleCancel}
      >
        <p>Some contents From  Modal...1</p>
        <p>Some contents From  Modal...1</p>
        <p>Some contents From  Modal...1</p>
      </Modal>
     )  
    }
}

export default Modal1