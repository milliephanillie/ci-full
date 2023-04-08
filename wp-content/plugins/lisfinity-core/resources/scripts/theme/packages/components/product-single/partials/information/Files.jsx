/* global lc_data, React */
/**
 * Dependencies.
 */
import { map } from 'lodash';
import ReactSVG from 'react-svg';
import FileIcon from '../../../../../../../images/icons/text-align-justify.svg';

function Files(props) {
  const { files } = props;

  return (
    <section className="product--files mb-40">
      <h5 className="font-bold text-grey-1000">{lc_data.jst[585]}</h5>
      <div className="product--files-wrapper flex flex-wrap mt-20">
        {map(files, (file, index) => {
          return (
            <a key={index} href={file.url || '#'} target="_blank"
               className="product--file flex-center mr-10 text-blue-700 hover:underline">
              <ReactSVG
                src={`${lc_data.dir}dist/${FileIcon}`}
                className="relative mr-4 w-10 h-10 fill-blue-700"
              />
              <span>{file.title || ''}</span>
            </a>
          );
        })}
      </div>
    </section>
  );
}

export default Files;
