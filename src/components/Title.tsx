function Title({ title }: { title: string }) {
  return (
    <>
      <h1 className="text-4xl font-bold text-center mb-4">{title}</h1>
      <hr className="w-[3rem] h-[3px] bg-black mx-auto" />
    </>
  );
}

export default Title;
