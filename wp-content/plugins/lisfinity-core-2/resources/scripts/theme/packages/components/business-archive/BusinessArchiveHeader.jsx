/* global lc_data, React */

import Breadcrumb from '../partials/Breadcrumb';

const BusinessArchiveHeader = (props) => {
  const { options } = props;

  return (
    <header
      className={`relative py-60 bg-cover bg-bottom bg-grey-1000 bg-${options['background-position'] ? options['background-position'] : 'center'}`}
      style={{ backgroundImage: `url(${options['background-image'] || ''})` }}>
        <span
          className="absolute top-0 left-0 w-full h-full z-1"
          style={{ backgroundColor: `rgba(${options['background-overlay']})` }}
        >
      </span>
      <div className="header--content relative container z-2">
        <h1 className="mb-6 text-white">{options['title']}</h1>
        <Breadcrumb title={options['title']} style="white"/>
      </div>
    </header>
  )
}

export default BusinessArchiveHeader;
