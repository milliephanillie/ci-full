/* global lc_data, React */
import ReactSVG from 'react-svg';
import ImportIcon from '../../../../images/icons/exit-down.svg';
import { __ } from '@wordpress/i18n';
import { map, isEmpty } from 'lodash';
import { useEffect, useState } from '@wordpress/element';
import axios from 'axios';
import { Fragment } from 'react';
import LoaderIcon from '../../../../images/icons/loader-rings.svg';

const TaxonomyImport = (props) => {
  const { taxonomy, terms, taxonomies } = props;
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [code, setCode] = useState('');
  const [parent, setParent] = useState(null);
  const [delimiter, setDelimiter] = useState('');
  const [completed, setCompleted] = useState(false);
  const [parentTerms, setParentTerms] = useState(null);

  useEffect(() => {
    const taxTerms = [{ id: 0, name: lc_data.jst[97] }];
    taxonomy?.parent && terms && map(terms, term => {
      if (term.taxonomy === taxonomy.parent) {
        taxTerms.push({ id: term.term_id, name: term.name });
      }
    });
    setParentTerms(!isEmpty(taxTerms) ? taxTerms : null);
  }, [terms]);

  const importFields = () => {
    setLoading(true);

    const headers = { 'X-WP-Nonce': lc_data.nonce };
    const data = {
      fields: code,
      taxonomy: taxonomy.slug,
      delimiter,
    };
    if (parent) {
      data.parent_id = parent;
    }
    axios({
      url: lc_data.taxonomy_import,
      method: 'POST',
      credentials: 'same-origin',
      headers,
      data,
    }).then(response => {
      setLoading(false);
      if (response.data.error) {
        setError(response.data.message);
      }
      if (response.data.success) {
        setTimeout(() => {
          location.reload();
        }, 300);
      }
      setCompleted(true);
    });
  };

  return (
    <div className="w-full">
      <h4 className="mb-10 font-semibold text-base">{__('Import Fields', 'lisfinity-core')}</h4>
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
      {error !== null &&
      <div className="flex items-center font-bold text-green-700">
        {error}
      </div>}
      {!error && completed &&
      <div className="flex items-center font-bold text-green-700">
        {lc_data.jst[84]}
      </div>}
      {!loading && !completed &&
      <Fragment>
        <div className="flex flex-col mt-10">
          <label htmlFor="importDelimiter" className="lisfinity-label mb-6">{lc_data.jst[702]}</label>
          <div
            className="inline-block w-full bg-grey-100 border border-grey-200 rounded"
            style={{
              width: 40,
            }}
          >
            <input type="text"
                   id="importDelimiter"
                   className="w-full text-center"
                   defaultValue={delimiter}
                   onChange={(e) => setDelimiter(e.target.value)}
            />
          </div>
          <span className="text-sm mt-6 text-grey-700 leading-snug">{lc_data.jst[703]}</span>
        </div>

        {parentTerms && parentTerms.length > 1 &&
        <div className="flex flex-col w-full">
          <label htmlFor="importParent" className="lisfinity-label mb-6">{lc_data.jst[699]}</label>
          <select
            id="importParent"
            className="p-20 w-full bg-grey-100 border border-grey-200 rounded"
            defaultValue={parent}
            onChange={(e) => setParent(e.target.value)}
          >
            {map(parentTerms, term => <option key={term.id} value={term.id}>{term.name}</option>)}
          </select>
          <span className="text-sm mt-6 text-grey-700 leading-snug">{lc_data.jst[700]}</span>
        </div>
        }

        <div className="mt-10 w-full">
          <label htmlFor="importFields" className="flex lisfinity-label mb-6">{lc_data.jst[701]}</label>
          <textarea
            id="importFields"
            rows={10}
            className="p-20 w-full bg-grey-100 border border-grey-200 rounded"
            defaultValue={code}
            onChange={(e) => setCode(e.target.value)}
          />
          <span className="text-sm mt-6 text-grey-700 leading-snug">{lc_data.jst[698]}</span>
        </div>

        <div className="mt-20">
          <button
            type="button"
            id="btnImport"
            onClick={(e) => importFields(e)}
            className="flex justify-center items-center py-12 px-24 bg-yellow-700 hover:bg-yellow-800 rounded font-bold text-base text-white"
            disabled={'' === code}
          >
            <ReactSVG
              src={`${lc_data.dir}dist/${ImportIcon}`}
              className="relative mr-8 w-20 h-20 fill-white"
              style={{ top: '-2px' }}
            />
            <span>{lc_data.jst[85]}</span>
          </button>
        </div>
      </Fragment>
      }

    </div>
  );

};

export default TaxonomyImport;
