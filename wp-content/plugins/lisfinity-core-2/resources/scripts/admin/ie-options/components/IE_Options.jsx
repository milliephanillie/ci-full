/* global lc_data, React */
import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import GroupExport from '../../taxonomies/components/GroupExport';
import GroupImport from '../../taxonomies/components/GroupImport';

const IE_Options = (props) => {

  useEffect(() => {
  }, []);

  return (
    <div className="flex flex-col">
      <div className="flex flex-wrap">
        <p
          className="text-base text-grey-500">{lc_data.jst[0]}</p>
      </div>

      <div className="flex justify-between mt-20">
        <GroupExport url={lc_data.options_export}/>
        <GroupImport url={lc_data.options_import}/>
      </div>

    </div>
  )
}

export default IE_Options;
