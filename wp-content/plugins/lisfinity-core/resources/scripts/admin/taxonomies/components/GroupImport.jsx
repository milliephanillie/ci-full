/* global lc_data, React */
import ReactSVG from 'react-svg';
import ImportIcon from '../../../../images/icons/exit-down.svg';
import { __ } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';
import axios from 'axios';
import { Fragment } from 'react';
import LoaderIcon from '../../../../images/icons/loader-rings.svg';

const GroupImport = (props) => {
  const [loading, setLoading] = useState(false);
  const [code, setCode] = useState('');
  const [completed, setCompleted] = useState(false);

  useEffect(() => {
    if (loading) {
      const exportBtn = document.getElementById('btnExport');
      if (exportBtn) {
        exportBtn.classList.add('pointer-events-none', 'opacity-25');
      }
    }
  });

  const importFields = (e) => {
    e.preventDefault();
    if (!confirm(lc_data.jst[82])) {
      return false;
    }
    setLoading(true);

    const headers = { 'X-WP-Nonce': lc_data.nonce };
    axios({
      url: props.url || lc_data.import_fields,
      method: 'POST',
      credentials: 'same-origin',
      headers,
      data: {
        fields: code,
      }
    }).then(response => {
      setLoading(false);
      if (response.data.import_terms) {
        importTerms(response.data.terms);
      }
    });
  }

  const importTerms = (terms) => {
    setLoading(true);

    const headers = { 'X-WP-Nonce': lc_data.nonce };
    axios({
      url: lc_data.import_terms,
      method: 'POST',
      credentials: 'same-origin',
      headers,
      data: {
        fields: terms,
      }
    }).then(response => {
      setLoading(false);
      setCompleted(true);
    });
  }

  return (
    <div className="w-full sm:w-48%">
      <h5 className="mb-10 font-semibold text-base">{__('Import Fields', 'lisfinity-core')}</h5>
      {loading &&
      <Fragment>
        <div className="flex items-center -ml-10">
          <ReactSVG
            src={`${lc_data.dir}dist/${LoaderIcon}`}
            className="relative"
          />
          {lc_data.jst[83]}
        </div>
        <span className="fixed top-0 left-0 w-full h-full z-10"></span>
      </Fragment>
      }
      {completed &&
      <div className="flex items-center font-bold text-green-700">
        {lc_data.jst[84]}
      </div>}
      {!loading && !completed &&
      <Fragment>
        <div>
          <button
            type="button"
            id="btnImport"
            onClick={(e) => importFields(e)}
            className="flex justify-center items-center py-12 px-24 bg-yellow-700 hover:bg-yellow-800 rounded font-bold text-base text-white"
          >
            <ReactSVG
              src={`${lc_data.dir}dist/${ImportIcon}`}
              className="relative mr-8 w-20 h-20 fill-white"
              style={{ top: '-2px' }}
            />
            <span>{lc_data.jst[85]}</span>
          </button>
        </div>

        <div className="mt-10 w-full">
        <textarea
          rows={10}
          className="p-20 w-full bg-grey-100 border border-grey-200 rounded"
          defaultValue={code}
          onChange={(e) => setCode(e.target.value)}
        />
        </div>
      </Fragment>
      }

    </div>
  );

}

export default GroupImport;
