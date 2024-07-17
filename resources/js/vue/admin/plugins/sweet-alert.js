import SweetAlert from "sweetalert2";
import _ from "lodash";

export const showAlert = (message, options = {}) => {
    return SweetAlert.fire({
        titleText: message,
        ...options,
    });
};

export const showConfirmation = (message, options = {}) => {
    return SweetAlert.fire({
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Save",
        cancelButtonText: `Don't save`,
        titleText: message,
        ...options,
    });
};

export const showWarning = (message, options = {}) => {
    return SweetAlert.fire({
        icon: "warning",
        titleText: message,
        ...options,
    });
};

export const showError = (message, options = {}) => {
    if (_.isObject(message)) {
        message = _.get(
            message,
            "response.data.message",
            _.get(message, "message")
        );
    }

    return SweetAlert.fire({
        icon: "error",
        titleText: message,
        ...options,
    });
};

export const showSuccess = (message, options = {}) => {
    return SweetAlert.fire({
        icon: "success",
        titleText: message,
        ...options,
    });
};

export default SweetAlert;
