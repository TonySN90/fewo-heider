import useScrollBackground from "../../hooks/useScrollBackground";
import Description from "./Description";
import HouseImages from "./HouseImages";
import InsightLayout from "./InsightLayout";
import InsightsCarousel from "./InsightsCarousel";
import Title from "./Title";

function Insights() {
  const { ref, sectionStyle } = useScrollBackground();

  return (
    <section ref={ref} style={sectionStyle}>
      <InsightLayout>
        <Title />
        <HouseImages />
        <Description />
        <InsightsCarousel />
      </InsightLayout>
    </section>
  );
}

export default Insights;
