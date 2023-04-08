/* global lc_data, React */
import { Fragment } from '@wordpress/element';

/**
 * Dependencies
 */

const Login = (props) => {

  return (
    <div className="modal--product-info flex flex-column flex-wrap p-30 pb-40">
      <p className="w-full font-bold text-grey-700">
        {props.message || lc_data.jst[437]}
      </p>
      <div className="flex mt-10">
        <a
          href={lc_data.page_login}
          className="btn bg-blue-700 inline-flex rounded text-white"
        >
          {lc_data.jst[419]}
        </a>
      </div>
    </div>
  );
};

export default Login;
