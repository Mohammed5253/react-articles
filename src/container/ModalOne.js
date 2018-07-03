import React, {Component} from 'react';

import {connect} from 'react-redux';
import { Button } from 'antd';
import * as action from '../_actions/actions'
import ModalRoot from '../components/ModalRoot';


class ModalOne extends Component {
    // constructor(props){
    //     super(props);
       
    // }
    showModal = () => {
        this.props.setModal({type:"SHOW_MODAL"})
    }

   

    handleCancel = (e) => {
        this.props.resetModal({type:"HIDE_MODAL"})
    }

    render(){
        const props = {status: this.props.status, handleCancel: this.handleCancel};
        return(
            <div>
                <Button type="primary" onClick={this.showModal}>Modal 1</Button>
                <ModalRoot modalType="MODAL1" modalProps={props}/>
            </div>
        )
    }
}
const mapStateToProps = (payload) => {
    console.log(payload)
    return {
        status:payload.show,
       
    }
} 
export default connect(mapStateToProps,action) (ModalOne);