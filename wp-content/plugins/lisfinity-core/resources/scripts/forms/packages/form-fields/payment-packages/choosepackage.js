import {useEffect} from "@wordpress/element";
import {useSelector} from "react-redux";
import {map, get, omit, isEmpty, find} from 'lodash';
import he from 'he';


const ChoosePackage  = (props) => {
    const {packages} = props;
    const allData = useSelector(state => state);
    const packageItems = packages.map((item) => {
        <h1>{item.post_title}</h1>
    });

    useEffect(() => {
        if (!allData.costs['total']) {
            if (allData.costs === 0) {
                delete allData.costs;
                allData.costs = {};
            }
            allData.costs['total'] = {};
        }
        if (!allData.costs['total']['promo']) {
            allData.costs['total']['promo'] = 0;
        }
    }, []);

    return [
        <div style={{
            width: '100%',
        }} className={"packages-wrapper"}>
            {map(packages, (item) => {
                return (!isEmpty(item) &&
                        <div style={{
                            display: 'flex',
                            border: '1px solid rgba(0,0,0,0.1)',
                            marginBottom: '20px',
                            justifyContent: 'space-between',
                        }} className={"package--card"}>
                            <div className={"package--header"} style={{
                                display: 'grid',
                                textAlign: 'center',
                                padding: '20px',
                                background: '#eee',
                                width: '25%',
                            }}>
                                <div style={{
                                    display: 'flex',
                                    alignItems: 'flex-end',
                                    justifyContent: 'center',
                                }} className={"pacakge-title"}>
                                    <h3 style={{
                                        fontSize: '16px'
                                    }}>{item.post_title}</h3>
                                </div>
                                <div style={{
                                    fontSize: '28px'
                                }} className={"packge-meta-price"}>
                                    <span>{"$" + item.meta.price}</span>
                                </div>
                            </div>
                            <div style={{
                                padding: '20px',
                                width: '55%',
                            }} className={"package--body"}>
                                <div style={{
                                    marginBottom: '20px',
                                }} className={"package-description"}>
                                    <p>{item.post_content}</p>
                                </div>
                                <div className={"package-meta"}>
                                    <div className={"packge-meta-features"}>
                                        <ul>
                                        {!isEmpty(item.package.features) &&
                                        map(item.package.features, (feature ,i) => {
                                            return (
                                                <li style={{
                                                    listStyle: 'none'
                                                }} dangerouslySetInnerHTML={{ __html: he.decode(feature['package-feature'])}} />
                                            )
                                        })}
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div style={{
                                padding: '40px',
                                textAlign: 'center',
                                display: 'flex',
                                justifyContent: 'center',
                                alignItems: 'center',
                            }} className={"package--choice"}>
                                <input style={{
                                    accentColor: '#3ebd93',
                                    width: '35px',
                                    height: '35px',
                                }} type={"radio"} name={"choose_package"} />
                            </div>
                        </div>
                )
            })}
        </div>
    ]
}

export default ChoosePackage;