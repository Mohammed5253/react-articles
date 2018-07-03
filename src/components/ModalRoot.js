import React from 'react';
import Modal1 from '../components/Modals/Modal1';
import Modal2 from '../components/Modals/Modal2';
import { connect } from 'react-redux';
import PostModal from './Modals/PostModal';

const MODAL_COMPONENTS = {
  'MODAL1': Modal1,
  'MODAL2': Modal2,
  'CREATE_ARTICLE':PostModal
  /* other modals */
}

const ModalRoot = ({ modalType, modalProps }) => {

    console.log(modalType,modalProps)
  if (!modalType) {
    return <span /> // after React v15 you can return null here
  }

  const SpecificModal = MODAL_COMPONENTS[modalType]
  return <SpecificModal {...modalProps} />
}
const mapStateToProps = (state) => {
    return {
        state: state.modal
    };
  }
export default connect(mapStateToProps)(ModalRoot)
