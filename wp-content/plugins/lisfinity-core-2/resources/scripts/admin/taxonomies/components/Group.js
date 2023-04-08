/* global lc_data */
/**
 * External dependencies.
 */
import { map, isEmpty } from 'lodash';
import ReactSVG from 'react-svg';
import PencilIcon from '../../../../images/icons/pencil.svg';
import TrashIcon from '../../../../images/icons/trash.svg';
import EyeIcon from '../../../../images/icons/eye.svg';
import ReactTooltip from 'react-tooltip';
import { Fragment, useEffect, useRef, useState } from '@wordpress/element';

const Group = (props) => {
  const [maxTaxonomies, setMaxTaxonomies] = useState(10);
  const [allTaxonomies, setAllTaxonomies] = useState(false);
  const { group, taxonomies } = props;
  const groupRef = useRef(null);

  const adminLink = `${lc_data.admin_url}admin.php?page=custom-fields-`;
  const permalink = group ? `${adminLink}${group.slug}` : `${adminLink}common`;
  const groupName = group ? group.slug : 'common';
  let taxonomyCount = 0;

  const seeAll = () => {
    if (!allTaxonomies) {
      setAllTaxonomies(true);
      setMaxTaxonomies(999);
    } else {
      setAllTaxonomies(false);
      setMaxTaxonomies(10);
    }
  };

  useEffect(() => {
    if (groupRef) {
      setTimeout(() => {
        groupRef.current.classList.remove('animate');
      }, 600);
    }
  }, []);

  return (
    <div
      ref={groupRef}
      className="font-sans cf-group flex justify-between items-center mt-10 p-20 bg-white rounded shadow-theme overflow-hidden animate"
      style={{ height: !allTaxonomies ? 76 : 'auto' }}
      {...props.provided.dragHandleProps}
    >
      <div className="cf-group-name pr-10 w-1/4">
        {group ?
          <a href={permalink} className="relative no-underline hover:underline">
            {group?.premium === 'yes' &&
            <span className="absolute text-sm text-red-600" style={{ right: -6, top: -10 }}>*</span>}
            <h3 className="inline-block font-bold text-lg text-grey-1100">{group.plural_name}</h3>
          </a>
          :
          <a href={permalink} className="no-underline hover:underline">
            <h3 className="inline-block font-bold text-lg">{lc_data.jst[71]}</h3>
          </a>
        }
      </div>
      <div className="cf-group--taxonomies flex flex-wrap w-2/4 pr-10">
        {taxonomies[groupName] ?
          map(taxonomies[groupName], (taxonomy, index) => {
            taxonomyCount += 1;
            return (
              taxonomy && taxonomyCount <= maxTaxonomies &&
              <span key={index} className="cf-group--taxonomy mr-4">
                  {taxonomy.single_name}
                {taxonomyCount !== taxonomies[groupName].length && taxonomyCount !== maxTaxonomies && ','}
                </span>
            );
          }) : <span className="text-grey-500">{lc_data.jst[78]}</span>}

        {taxonomies[groupName] &&
        <div className="flex w-full">
          <button onClick={() => seeAll()}
                  className="text-blue-700 hover:underline">
            {taxonomies[groupName].length > maxTaxonomies && lc_data.jst[622]}
            {(taxonomies[groupName].length < maxTaxonomies && taxonomies[groupName].length > 10) && lc_data.jst[623]}
          </button>
        </div>
        }
      </div>
      {group ?
        <div className="cf-group--actions flex justify-end w-1/6 ml-auto">

          <a
            href={permalink}
            className="cf-group--delete flex justify-center items-center mr-10 min-w-36 h-36 rounded-full bg-yellow-300"
            data-tip={lc_data.jst[619]}
          >
            <ReactSVG
              src={`${lc_data.dir}dist/${PencilIcon}`}
              className="relative w-16 h-16 fill-grey-900"
            />
          </a>

          <button
            type="button"
            onClick={e => props.handleModal(e, group, true)}
            className="cf-group--edit flex justify-center items-center mr-10 min-w-36 h-36 rounded-full bg-green-300"
            data-tip={lc_data.jst[620]}
          >
            <ReactSVG
              src={`${lc_data.dir}dist/${PencilIcon}`}
              className="relative w-16 h-16 fill-grey-900"
            />
          </button>

          <button
            type="button"
            onClick={e => props.handleGroupDelete(e)}
            className="cf-group--delete flex justify-center items-center mr-10 min-w-36 h-36 rounded-full bg-red-200"
            data-tip={lc_data.jst[621]}
          >
            <ReactSVG
              src={`${lc_data.dir}dist/${TrashIcon}`}
              className="relative w-16 h-16 fill-grey-900"
            />
          </button>
        </div>
        :
        <div className="cf-group--actions flex justify-end w-1/6 ml-auto">
          <a
            href={permalink}
            className="cf-group--delete flex justify-center items-center min-w-36 h-36 rounded-full bg-yellow-300"
          >
            <ReactSVG
              src={`${lc_data.dir}dist/${PencilIcon}`}
              className="relative w-16 h-16 fill-grey-900"
              data-tip={lc_data.jst[619]}
            />
          </a>
        </div>
      }
      <ReactTooltip/>
    </div>
  );
};

export default Group;
