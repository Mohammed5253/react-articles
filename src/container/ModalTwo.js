import React, {Component} from 'react';

import {connect} from 'react-redux';
import {Button } from 'antd';

import * as action from '../_actions/actions';

import ModalRoot from '../components/ModalRoot';
class ModalTwo extends Component {
    // constructor(props){
    //     super(props);
        
    // }
    loginModal = () => {
        this.props.loginModal({type:"LOGIN_MODAL"})
    }

   

    handleCancel = (e) => {
        this.props.resetModal({type:"HIDE_MODAL"})
    }


    handleOk = (e) => {
        console.log(e);
        this.setState({
        visible: false,
        });
    }

    // handleCancel = (e) => {
    //     console.log(e);
    //     this.setState({
    //     visible: false,
    //     });
    // }

    render(){
        const props = {status: this.props.status, handleCancel: this.handleCancel};
        return(
            <div>
                <Button type="primary" onClick={this.loginModal}>Modal 2 </Button>
                <ModalRoot modalType="MODAL2" modalProps={props}/>
            </div>
        )
    }
}
const mapStateToProps = (payload) => {
    console.log(payload)
    return {
        status:payload.loginshow,
       
    }
} 
export default connect(mapStateToProps,action) (ModalTwo);