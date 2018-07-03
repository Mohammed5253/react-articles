import React, {Component} from 'react';

import { Modal} from 'antd';
// import * as action from '../_actions/actions'


class Modal2 extends Component {
    render(){
       console.log(this.props)
     return (
        <Modal
        title="Basic Modal"
        visible={this.props.status}
        onOk={this.props.handleCancel}
        onCancel={this.props.handleCancel}
      >
        <p>Some contents From  Modal...2</p>
        <p>Some contents From  Modal...2</p>
        <p>Some contents From  Modal...2</p>
      </Modal>
     )  
    }
}

export default Modal2