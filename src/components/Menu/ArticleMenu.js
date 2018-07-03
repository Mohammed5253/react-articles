import React, { Component } from 'react';
import {
    Container,
    Divider,
    Dropdown,
    Grid,
    Header,
    Image,
    List,
    Menu,
    Segment,
  } from 'semantic-ui-react'
import {Button } from 'antd';
import {connect} from 'react-redux';

import ModalRoot from '../../components/ModalRoot'
import * as action from '../../_actions/actions';

class ArticleMenu extends Component {
    showPostModal = () => {
        this.props.showPostModal({type:"SHOW_POST_MODAL"})
    }
     handleCancel = (e) => {
        this.props.resetModal({type:"HIDE_MODAL"})
    }
    render(){
    const props = {status: this.props.status, handleCancel: this.handleCancel};
    return(
        <div>
           <Menu fixed='top' inverted>
                <Container>
                    <Menu.Item as='a' header>
                    ARTICLE
                    </Menu.Item>
                    <Menu.Item as='a'>All Articles</Menu.Item>
                    <Menu.Item as='b'> 
                        <Button type="primary" onClick={this.showPostModal}>Write An Article</Button>
                        <ModalRoot modalType="CREATE_ARTICLE" modalProps={props}/>
                    </Menu.Item>
                </Container>
            </Menu>
        </div>
    )
    }
}

const mapStateToProps = (payload) => {
    console.log(payload)
    return {
        status:payload.showpostmodal,
        imagePreviewUrl:payload.imagePreviewUrl

       
    }
} 
export default connect(mapStateToProps,action)(ArticleMenu);