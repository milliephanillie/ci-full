/* global lc_data, React */
import ReactSVG from 'react-svg';
import ExportIcon from '../../../../images/icons/exit-up.svg';
import FilesIcon from '../../../../images/icons/files.svg';
import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';
import axios from 'axios';
import copy from 'copy-to-clipboard';
import LoaderIconWhite from '../../../../images/icons/loader-rings-white.svg';
import { Fragment } from 'react';

const GroupExport = (props) => {
  const [loading, setLoading] = useState(false);
  const [code, setCode] = useState(null);
  const [notice, setNotice] = useState(null);

  const exportFields = (e) => {
    e.preventDefault();
    setLoading(true);
    setCode(null);
    setNotice(null);

    const headers = { 'X-WP-Nonce': lc_data.nonce };
    axios({
      url: props.url || lc_data.export_fields,
      method: 'GET',
      credentials: 'same-origin',
      headers,
    }).then(response => {
      setCode(response.data);
      setLoading(false);
    });
  };

  return (
    <div className="w-full sm:w-48%">
      <h5 className="mb-10 font-semibold text-base">{__('Export Fields', 'lisfinity-core')}</h5>
      <div>
        <button
          id="btnExport"
          type="button"
          onClick={(e) => exportFields(e)}
          className="flex justify-center items-center py-12 px-24 bg-green-700 hover:bg-green-800 rounded font-bold text-base text-white"
        >
          {loading &&
          <Fragment>
            <ReactSVG
              src={`${lc_data.dir}dist/${LoaderIconWhite}`}
              className="relative mr-10 w-20 h-20 fill-white"
              style={{ top: '-13px', left: '-12px', zoom: .8 }}
            />
            <span>{lc_data.jst[79]}</span>
          </Fragment>
          }
          {!loading &&
          <Fragment>
            <ReactSVG
              src={`${lc_data.dir}dist/${ExportIcon}`}
              className="relative mr-8 w-20 h-20 fill-white"
              style={{ top: '-2px' }}
            />
            <span>{lc_data.jst[79]}</span>
          </Fragment>
          }
        </button>
      </div>

      {code &&
      <div className="mt-10">
        <textarea
          rows={10}
          className="p-20 w-full bg-grey-100 border border-grey-200 rounded"
          defaultValue={code}
        />
        <div className="relative inline-block">
          <button
            type="button"
            onClick={(e) => {
              copy(`${code}`);
              setNotice(lc_data.jst[80]);
              setTimeout(() => {
                setNotice(null);
              }, 2000);
            }}
            className="relative flex justify-center items-center mt-10 py-6 px-12 bg-white rounded font-bold text-sm text-grey-1000"
          >
            <ReactSVG
              src={`${lc_data.dir}dist/${FilesIcon}`}
              className="relative mr-8 w-14 h-14 fill-grey-1000"
            />
            <span>{lc_data.jst[81]}</span>
          </button>

          {notice &&
          <span
            className="absolute flex items-center justify-center py-4 px-12 bg-grey-1000 rounded text-sm text-white"
            style={{
              top: '10px',
              right: '-64px',
              fontSize: '10px',
            }}
          >{notice}</span>
          }
        </div>
      </div>
      }
    </div>
  );

};

export default GroupExport;
